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
        @if (count($boughtProducts) > 0)
            @foreach ($boughtProducts as $boughtProduct)
                <tr>
                    <td>{{ $boughtProduct->order->payment_id }}</td>
                    <td>{{ $boughtProduct->seller->name }}</td>
                    <td>{{ $boughtProduct->quantity }}</td>
                    <td>
                        <form class="calificacionForm" id="calificacionForm_{{ $boughtProduct->id }}">
                            <p class="clasificacion d-flex justify-content-center flex-row">
                                <input type="hidden" class="form-control" name="bought_product_id"
                                    value="{{ $boughtProduct->id }}">
                                <input type="hidden" class="form-control" name="seller_id"
                                    value="{{ $boughtProduct->seller->id }}">

                                <input id="radio1_{{ $boughtProduct->id }}" type="radio" name="calification"
                                    @if ($boughtProduct->calification == 5) checked @endif value="5">
                                <label for="radio1_{{ $boughtProduct->id }}">★</label>

                                <input id="radio2_{{ $boughtProduct->id }}" type="radio" name="calification"
                                    @if ($boughtProduct->calification == 4) checked @endif value="4">
                                <label for="radio2_{{ $boughtProduct->id }}">★</label>

                                <input id="radio3_{{ $boughtProduct->id }}" type="radio" name="calification"
                                    @if ($boughtProduct->calification == 3) checked @endif value="3">
                                <label for="radio3_{{ $boughtProduct->id }}">★</label>

                                <input id="radio4_{{ $boughtProduct->id }}" type="radio" name="calification"
                                    @if ($boughtProduct->calification == 2) checked @endif value="2">
                                <label for="radio4_{{ $boughtProduct->id }}">★</label>

                                <input id="radio5_{{ $boughtProduct->id }}" type="radio" name="calification"
                                    @if ($boughtProduct->calification == 1) checked @endif value="1">
                                <label for="radio5_{{ $boughtProduct->id }}">★</label>
                            </p>
                            <button type="submit" id="submit_{{ $boughtProduct->id }}"
                                style="display: none;"></button>
                        </form>
                    </td>
                </tr>
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
        $(this).closest('form').find('button[type="submit"]').click();
    });

    $('.calificacionForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);




        $.ajax({
            url: "{{ route('add.calification') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(data) {
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
    });
</script>
