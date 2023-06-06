@extends('layouts.main')
@section('content')
    <style>
        p {
            margin: unset;
        }
    </style>
    <h1 class="title titlemargin">ENCUENTRA TU RELIQUIA</h1>
    <h2 class="title">Envíos gratis a cualquier punto de la península</h2>
    <div class="container">
        <div class="row">
            <div class="col-6" style="margin: auto">
                <div class="row">
                    <div class="form-group">
                        <label for="pmin">Precio mínimo</label>
                        <input class="form-control" type="number" step="0.01" id="pmin" name="pmin"
                            min="0" placeholder="min ...">
                    </div>

                    <div class="form-group">
                        <label for="pmax">Precio máximo</label>
                        <input class="form-control" type="number" step="0.01" id="pmax" name="pmax"
                            min="0" placeholder="max ...">
                    </div>
                    <div class="form-group">
                        <label for="pmax">Estado</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Todos</option>
                            <option value="nuevo">Nuevo</option>
                            <option value="usado">Usado</option>
                            <option value="estropeado">Estropeado</option>
                        </select>
                    </div>

                </div>
                <div class="col-12" style="text-align: center">
                    <button class="btn btn-success" id="btnFilter">Filtrar</button>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <div class="row" id="content_products">
            @include('products._partial_productos', $productos)
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('#btnFilter').click((e) => {
            $('#content_products').html('')
            let estado = $('#status').val().toLowerCase();
            let pmin = $('#pmin').val() ? $('#pmin').val() : 0;
            let pmax = $('#pmax').val() ? $('#pmax').val() : 999999999;
            let data = new FormData();
            data.append('estado', estado);
            data.append('pmin', pmin);
            data.append('pmax', pmax);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: '{{ route('product.filter') }}?estado=' + estado + '&pmin=' + pmin + '&pmax=' +
                    pmax + '',
                type: 'get',
                contentType: false,
                processData: false,
                data: data,
                success: function(data) {
                    $('#content_products').html(data.view)
                },
                error: function(error) {
                    //toastr.error(error.responseJSON.message);
                }
            });
        })
        $(document).ready(function() {
            $('.addCartBtn').click(function(e) {
                e.preventDefault();

                var product_id = $(this).data('product_id');

                addToCart(product_id);

            })
        })

        function addToCart(product_id) {
            var cart = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : {};

            //get the product details using ajax (/cart/{product_id})

            $.ajax({
                url: '/cart/get-product/' + product_id,
                type: 'get',
                async: false,
                success: function(response) {
                    //product looks like this:
                    /*
                        {
                            "id": 1,
                            "price": "10.00",
                            "title": "product1",
                            "stock": 10,
                            "description": "description1",
                            "foto": "Z2PIAt3yqKtm3fs1678866576jpg",
                            "status": "Nuevo",
                            "user_id": 1,
                            "created_at": "2023-06-04T01:13:46.000000Z",
                            "updated_at": "2023-06-04T01:13:46.000000Z"
                        }
                    */
                    let product = {
                        id: response.id,
                        title: response.title,
                        price: response.price,
                        stock: response.stock,
                        description: response.description,
                        foto: response.foto,
                        status: response.status,
                        user_id: response.user_id,
                        created_at: response.created_at,
                        updated_at: response.updated_at,
                        quantity: 1
                    }

                    if (cart.hasOwnProperty(product_id)) {
                        //check if adding one more would exceed the stock
                        if (cart[product_id]['quantity'] + 1 > product.stock) {
                            displayErrors('No hay suficiente stock');
                            return;
                        }
                        cart[product_id]['quantity']++
                    } else {
                        cart[product_id] = product;
                    }

                    localStorage.setItem('cart', JSON.stringify(cart));

                    displayNotification('Producto añadido al carrito');


                }
            });
        }

        function displayNotification(message) {
            toastr.success(message);
        }

        function displayErrors(errors) {
            toastr.error(errors);
        }
    </script>
@endsection
