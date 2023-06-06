@extends('layouts.main')



@section('content')
    <style>
        p {
            margin: unset;
        }

        .materials_list li {
            list-style: disclosure-closed;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img style="width: 100%" src="{{ asset('storage/productsImages/' . $producto->foto) }}">
            </div>
            <div class="col-12 col-md-6">
                <h1>{{ $producto->title }}</h1>
                <p>{{ $producto->description }}</p>

                @if ($producto->materiales)
                    <p>Materiales:</p>
                    <ul style="margin-left: 15px" class="materials_list">
                        @foreach ($producto->materiales as $materialPivot)
                            <li>{{ $materialPivot->material->name }}</li>
                        @endforeach

                    </ul>
                @endif
                @if ($producto->stock)
                    <p>Stock: {{ $producto->stock }}</p>
                @endif
                <p style="font-weight: bold;font-size: 16px">Precio: {{ $producto->price }}€</p>
                <p>Publicado por: {{ $producto->user->name }}</p>
                <p>Calificación: {{ $producto->user->seller->calificate }}</p>
                <p>Dirección:</p>
                <p>{{ $producto->user->addressUser->address . ' ' . $producto->user->addressUser->city->province . ' ' . $producto->user->addressUser->cp }}
                <div id="mapid" style="height: 180px;"></div>

                </p>
                @auth
                    @if ($producto->user->id != \Illuminate\Support\Facades\Auth::id())
                        <button type="button" class="btn btn-primary addCartBtn" data-product_id="{{ $producto->id }}"><i
                                class="fa fa-plus"></i> Añadir al carrito</button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet-src.js"></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet"></script>

    <!-- Esri Leaflet Geocoder -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css" />
    <script src="https://unpkg.com/esri-leaflet-geocoder"></script>

    <script>
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










        var address = "{{ $producto->user->addressUser->address }}";
        var city = "{{ $producto->user->addressUser->city->province }}";
        var postalCode = "{{ $producto->user->addressUser->cp }}";

        var fullAddress = address + ", " + city + ", " + postalCode;

        var map = L.map('mapid').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var geocodeService = L.esri.Geocoding.geocodeService({
            apikey: 'AAPKc507468e92a9497f95df17dc2881db6bKho4FvJSgeTraGh6966lg0_hwzdGpG4CXBVx7gTIYa9aEjao4MENdHAJb7Lg1XM7'
        });

        geocodeService.geocode().address(fullAddress).run(function(error, response) {
            if (error) {
                alert("error")
                console.log(error)
                return;
            }

            // Use the first result
            if (response.results && response.results.length > 0) {
                var firstResult = response.results[0];
                var latlng = firstResult.latlng;
                var address = firstResult.properties.LongLabel;

                map.setView(latlng, 13);

                L.marker(latlng).addTo(map)
                    .bindPopup(address)
                    .openPopup();
            }
        });
    </script>
@endsection
