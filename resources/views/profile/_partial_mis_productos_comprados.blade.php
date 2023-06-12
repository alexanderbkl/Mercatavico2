<table class="table" id="tableOrders">

    <thead class="thead-dark">
        <tr>
            <th scope="col">Id orden</th>
            <th scope="col">Vendedor</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Calificación</th>
        </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @foreach ($orders as $order)
                @foreach ($order->orderDetails as $orderDetail)
                    <tr>
                        <td>{{ $orderDetail->order_id }}</td>
                        <td>{{ $orderDetail->product->seller->user->name }}</td>
                        <td>{{ $orderDetail->quantity }}</td>
                        <td>
                            <form class="calificacionForm" id="calificacionForm_{{ $orderDetail->id }}">
                                <p class="clasificacion d-flex justify-content-center flex-row">
                                    <input type="hidden" class="form-control" name="bought_product_id"
                                        value="{{ $orderDetail->id }}">
                                    <input type="hidden" class="form-control" name="seller_id"
                                        value="{{ $orderDetail->product->seller->user->id }}">

                                    <input id="radio1_{{ $orderDetail->id }}" type="radio" name="calification"
                                        @if ($orderDetail->calification == 5) checked @endif
                                            @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador') disabled @endif value="5">
                                    <label for="radio1_{{ $orderDetail->id }}">★</label>

                                    <input id="radio2_{{ $orderDetail->id }}" type="radio" name="calification"
                                        @if ($orderDetail->calification == 4) checked @endif
                                                                                    @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador') disabled @endif value="4">
                                    <label for="radio2_{{ $orderDetail->id }}">★</label>

                                    <input id="radio3_{{ $orderDetail->id }}" type="radio" name="calification"
                                        @if ($orderDetail->calification == 3) checked @endif
                                                                                    @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador') disabled @endif value="3">
                                    <label for="radio3_{{ $orderDetail->id }}">★</label>

                                    <input id="radio4_{{ $orderDetail->id }}" type="radio" name="calification"
                                        @if ($orderDetail->calification == 2) checked @endif
                                                                                    @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador') disabled @endif value="2">
                                    <label for="radio4_{{ $orderDetail->id }}">★</label>

                                    <input id="radio5_{{ $orderDetail->id }}" type="radio" name="calification"
                                        @if ($orderDetail->calification == 1) checked @endif
                                                                                    @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador') disabled @endif value="1">
                                    <label for="radio5_{{ $orderDetail->id }}">★</label>
                                </p>
                                <button type="submit" id="submit_{{ $orderDetail->id }}"
                                    style="display: none;"></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @else
            <p>No hay pedidos</p>
        @endif

    </tbody>
</table>

<style>
    #form {
        width: 250px;
        margin: 0 auto;
        height: 50px;
    }

    #form p {
        text-align: left;
    }

    #form label {
        font-size: 20px;
    }

    input[type="radio"] {
        display: none;
    }

    label {
        color: grey;
    }

    .clasificacion {
        direction: rtl;
        unicode-bidi: bidi-override;
    }

    label:hover,
    label:hover~label {
        color: orange;
    }

    input[type="radio"]:checked~label {
        color: orange;
    }
</style>

<script>
    $('input[type="radio"]').on('click', function(e) {
        if ("{{ \Illuminate\Support\Facades\Auth::user()->rol->name }}" == 'administrador') {
            e.preventDefault();
            return;
        }
        $(this).closest('form').find('button[type="submit"]').click();
    });
    $('.calificacionForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);



        @if (\Illuminate\Support\Facades\Auth::user()->rol->name != 'administrador')
            $.ajax({
                url: "{{ route('add.calification') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(data) {
                    $(".modal-backdrop").remove(); // hide the overlay
                    $('body').removeClass("modal-open");
                    toastr.success('Calificación enviada');
                },
                error: function(data) {
                    toastr.error('Error al enviar la calificación: ' + data.responseText);
                    console.log(data.responseText)
                },
                cache: false,
                contentType: false,
                processData: false
            });
        @endif

    });
</script>
