<table class="table table-hover">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th class="text-center">Pricio unitario</th>
            <th class="text-center">Sub total</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="cartItems">



    </tbody>
</table>

<p id="user-credits" style="display: none;">{{ Auth::user()->credits }}

    <!-- Confirm delete cart Modal -->
<div class="modal fade" id="confirmDeleteCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="exampleModalLongTitle">¿Seguro que quieres eliminar este elemento del
                    carrito?</h5>
            </div>
            <input type="hidden" name="cartitem_id_delete_input">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <input type="hidden" name="cartitem_id_delete_input">
                <button type="button" class="btn btn-primary" id="confirm_delete_cart_btn"
                    data-dismiss="modal">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    //get cart items from localstorage
    $(document).ready(function() {
        //cart value looks like this:
        /*
            {"2":{"id":2,"title":"product2","price":"20.00","stock":20,"description":"description2","foto":"Z2PIAt3yqKtm3fs1678866576jpg","status":"Usado","user_id":2,"created_at":"2023-06-04T01:13:46.000000Z","updated_at":"2023-06-04T01:13:46.000000Z","quantity":4},"3":{"id":3,"title":"product3","price":"30.00","stock":30,"description":"description3","foto":"Z2PIAt3yqKtm3fs1678866576jpg","status":"Estropeado","user_id":3,"created_at":"2023-06-04T01:13:46.000000Z","updated_at":"2023-06-04T01:13:46.000000Z","quantity":2}}
        */

        let cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : {};
        let totalAmount = 0;

        //iterate over cart items and append them to the table body
        for (let itemId in cart) {
            let item = cart[itemId];
            totalAmount += item.price * item.quantity;

            let itemHtml = `
            <tr class="cart-item-${item.id}">
                <td class="col-sm-8 col-md-6">
                    <div class="media">
                        <div class="thumbnail pull-left" style="margin-right: 25px">
                            <img src="${'storage/productsImages/' + item.foto}" style="width: 72px; height: 72px;object-fit: cover">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">${item.title}</h4>
                            <h5 class="media-heading">Vendido por <span>${item.user_id}</span></h5>
                            <span>Estado: </span><span class="text-success"><strong>${item.status}</strong></span>
                        </div>
                    </div>
                </td>
                <td class="col-sm-1 col-md-1" style="text-align: center">${item.quantity} unidades</td>
                <td class="col-sm-1 col-md-1 text-center"><strong>${item.price} €</strong></td>
                <td class="col-sm-1 col-md-1 text-center"><strong>${item.price * item.quantity} €</strong></td>
                <td class="col-sm-1 col-md-1">
                    <button type="button" class="btn btn-danger deleteCartItem" data-id="${item.id}" data-toggle="modal" data-target="#confirmDeleteCartModal">
                        <span class="glyphicon glyphicon-remove deleteCartConfirm" data-id="${item.id}"></span> Eliminar
                    </button>
                </td>
            </tr>
        `;

            $("#cartItems").append(itemHtml);
        }

        //the total row

        let totalRow = `
        <tr>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td>
                <h3>Total</h3>
            </td>
            <td class="text-right">
                <h3><strong>€${totalAmount}</strong></h3>
            </td>
        </tr>
        `;

        //descuento puntos row



        //Apply total and discount
        let userCredits = parseInt($('#user-credits').text(), 10);
        let discount = userCredits > totalAmount ? totalAmount : userCredits;

        let discountRow = `
        <tr>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td>
                <h4>Descuento puntos:</h4>
            </td>
            <td class="text-right">
                <h4>
                    <strong id="discount">${discount} puntos</strong>
                </h4>
            </td>
        </tr>
        `;

        let payRow = `
        <tr>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td>
                <form action="{{ url('charge') }}" method="post">
                    <input type="hidden" name="amount" id="totalAmount" />
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-success" id="payBtn" name="submit" value="Pagar   €)">
                </form>


            </td>
        </tr>
        `;

        $("#cartItems").append(totalRow);
        $("#cartItems").append(discountRow);
        $("#cartItems").append(payRow);

        $('#totalAmount').text(totalAmount);
        $("discount").text(discount + ' puntos');

        //Update amount in form
        let finalAmount = totalAmount - discount;
        $("input[name='amount']").val(finalAmount < 0 ? 0 : finalAmount);
        //paybtn:
        $('#payBtn').click(function(e) {
            if (finalAmount <= 0) {
                e.preventDefault();
            }
        })
        $("#payBtn").val('Pagar ' + finalAmount + ' €');
        if (finalAmount > 0) {
            $('#payBtn').removeClass('disabled');
        } else {
            $('#payBtn').addClass('disabled');
        }

        //Delete cart item
        $('.deleteCartItem').click(function(e) {

            let itemId = $(this).data('id');
            console.log(itemId);
            //append the item id to the hidden input in the modal
            $('input[name="cartitem_id_delete_input"]').val(itemId);
        })

        $('#confirm_delete_cart_btn').click(function() {
            let itemId = $('input[name="cartitem_id_delete_input"]').val();
            delete cart[itemId];
            localStorage.setItem('cart', JSON.stringify(cart));
            $(`.cart-item-${itemId}`).remove();
        })



    })
</script>
