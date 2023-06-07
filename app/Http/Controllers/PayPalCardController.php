<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\BoughtProducts;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


/** All Paypal Details class **/


use Omnipay\Omnipay;
use Symfony\Component\Console\Output\ConsoleOutput;

class PayPalCardController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    /**
     * Call a view.
     */
    public function index()
    {
        return view('payment');
    }

    /**
     * Initiate a payment on PayPal.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function charge(Request $request)
    {
        try {
        $output = new ConsoleOutput();
        $encodedCartItems = $request->input('cart');
        $decodedCartItems = urldecode($encodedCartItems);
        $cartItems = json_decode($decodedCartItems, true);
        //cartItems looks like {"1":{"id":1,"title":"product1","price":"10.00","stock":10,"description":"description1","foto":"Z2PIAt3yqKtm3fs1678866576jpg","status":"Nuevo","user_id":1,"created_at":"2023-06-04T01:13:46.000000Z","updated_at":"2023-06-04T01:13:46.000000Z","quantity":10}}

            $output->writeln(json_encode($cartItems));

            foreach ($cartItems as $item) {
                $output->writeln($item['id']);
            }

        $output->writeln("Username: ".Auth::user()->name);
        if (count($cartItems) == 0) {
            return \redirect()->back()->with('warning', 'No tienes ningún elemento en el carrito.');
        }


            $amountCart = CartHelper::calcTotalAmount($cartItems);
            $finalAmount = Auth::user()->credits < $amountCart ?
                $amountCart - Auth::user()->credits  :
                0;
            $user = User::find(Auth::id());
            if ($amountCart >= $user->credits){
                $user->credits  = 0;
            }else{
                $user->credits = $user->credits - $amountCart;
            }

            $user->save();
            session(['cart' => $cartItems]);

            if($finalAmount==0){
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'transaction' => null,
                    'total' => $finalAmount,
                    'pay' => 1,
                    'payment_id' => null,
                    'currency' => 'EUR',
                    'date' => now(),
                ]);


                foreach ($cartItems as $item) {
                    //from product stock, remove the quantity bought
                    $product = Product::find($item['id']);
                    //control that the stock is not negative
                    if ($product->stock < $item['quantity']) {
                        return redirect()->route('profile.edit')
                            ->with('error', 'No hay suficiente stock de ' . $product->title);
                    }
                    $product->stock -= $item['quantity'];
                    $product->save();
                    OrderDetail::create([
                        'product_id' => $item['id'],
                        'order_id' => $order->id,
                        'quantity' => $item['quantity'],
                    ]);
                    BoughtProducts::create([
                        'product_id' => $item['id'],
                        'user_id' => Auth::id(),
                        'seller_id' => $item['user_id'],
                        'quantity' => $item['quantity'],
                        'order_id' => $order->id,
                        'price' => $item['price'],
                    ]);
                    if (User::find($item['user_id'])->rol->name == "miembro") {
                        $user = User::find($item['user_id']);
                        $user->credits += ($item['price'] * $item['quantity']);
                        $user->save();
                    }
                }




                return redirect()->route('profile.edit')
                    ->with('success', 'Pedido realizado correctamente')
                    ->with('deleteCart', true);

            }

            $response = $this->gateway->purchase(array(
                'amount' => $finalAmount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error'),
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect(); // this will automatically forward the customer
            } else {
                // not successful
                return 'error en la redirección '.$response->getMessage();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $output->writeln($e->getMessage());
            return $e->getMessage();
        }

    }

    /**
     * Charge a payment and store the transaction.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function success(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                // El cliente ha completado el pago
                $arr_body = $response->getData();
                $output = new ConsoleOutput();
                $output->writeln("array body: ");
                $output->writeln(json_encode($arr_body));
                // Inserta los datos de transaccion en la bd
                $payment = Payment::create([
                    'payment_id' => $arr_body['id'],
                    'payer_id' => $arr_body['payer']['payer_info']['payer_id'],
                    'payer_email' => $arr_body['payer']['payer_info']['email'],
                    'amount' => $arr_body['transactions'][0]['amount']['total'],
                    'currency' => $arr_body['transactions'][0]['amount']['currency'],
                    'payment_status' => $arr_body['state'],
                    'payment_method' => $arr_body['payer']['payment_method'],
                ]);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'transaction' => $payment->payment_id,
                    'total' => $payment->amount,
                    'currency' => $payment->currency,
                    'pay' => 1,
                    'payment_id' => $payment->id,
                    'date' => date('Y-m-d H:i:s'),
                ]);








                $cartItems = session('cart', []);
                foreach ($cartItems as $item) {
                    //from product stock, remove the quantity bought
                    $product = Product::find($item['id']);
                    //control that the stock is not negative
                    if ($product->stock < $item['quantity']) {
                        return redirect()->route('profile.edit')
                            ->with('error', 'No hay suficiente stock de ' . $product->title);
                    }
                    $product->stock -= $item['quantity'];
                    //if the stock is 0 or less, delete the product
                    $product->save();

                    OrderDetail::create([
                        'product_id' => $item['id'],
                        'order_id' => $order->id,
                        'quantity' => $item['quantity'],
                    ]);
                    BoughtProducts::create([
                        'product_id' => $item['id'],
                        'user_id' => Auth::id(),
                        'seller_id' => $item['user_id'],
                        'quantity' => $item['quantity'],
                        'order_id' => $order->id,
                        'price' => $item['price'],
                    ]);
                    if (User::find($item['user_id'])->rol->name == "miembro") {
                        $user = User::find($item['user_id']);
                        $user->credits += ($item['price'] * $item['quantity']);
                        //sum to user seller cred_total the price of the product multiplied by the quantity
                        $output = new ConsoleOutput();
                        $user->seller->cred_total += ($item['price'] * $item['quantity']);
                        $output->writeln("cred_total: " . $user->seller->cred_total);
                        $user->save();
                        $user->seller->save();
                    }
                }
                session()->forget('cart');

                return redirect()->route('profile.edit')
                    ->with('success', 'Pedido realizado correctamente')
                    ->with('deleteCart', true);

            } else {
                return $response->getMessage();
            }
        } else {
            return redirect()->route('cart.index')->with('error', 'Pago cancelado.');
        }
    }

    /**
     * Error Handling.
     */
    public function error()
    {
        return redirect()->route('cart.index')->with('error', 'Pago cancelado.');
    }


}
