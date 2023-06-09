@extends('layouts.main')
@section('content')
    <style>
        p {
            margin: unset;
        }
    </style>
    <section class="video">
        <video loop="true" autoplay="autoplay" muted>
            <source src="{{ asset('sources/data/mercatavico.mp4') }}" type="video/mp4">
        </video>
    </section>

    <section class="titlemargin" id="slogan">
        <h1>Mercatavico</h1>
        <h2>Conoce el pasado</h2>
    </section>

    <br><br><br><br>

    <h1 class="title">Nuestros productos</h1>
    <br>
    <div class="container">
        <div class="row">
            @foreach ($productos as $producto)
                <div class="col-12 col-md-4" style="margin-top: 40px">
                    <div class="card" style="width: 18rem;">
                        <div class="card-head">
                            <img style="width:100%;height: 200px;object-fit: cover"
                                src="{{ asset('storage/productsImages/' . $producto->foto) }}">
                        </div>
                        <div class="card-body" style="text-align: start">
                            <h3>{{ $producto->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($producto->description, 100) }}</p>
                            <p>{{ $producto->price }}€</p>
                        </div>
                        <div class="card-footer" style="text-align: center">
                            <a class="btn btn-success" href="{{ route('product.show', $producto->id) }}">Ver producto</a>
                            @auth
                                @if ($producto->seller->user->id != \Illuminate\Support\Facades\Auth::id())
                                    <button type="button" data-product_id="{{ $producto->id }}"
                                        class="btn btn-primary addCartBtn"><i class="fa fa-plus"></i> Añadir al
                                        carrito</button>
                                @endif
                            @endauth

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div id="carrousel-content">
        <div id="carrousel-box">
            <div class="carrousel-element">
                <a href="productos.html"><img class="images" src="{{ asset('sources/data/varios.png') }}"
                        alt="varios"></a>
            </div>
            <div class="carrousel-element">
                <a href="productos.html"><img class="images" src="{{ asset('sources/data/deco.png') }}"
                        alt="decoración"></a>
            </div>
            <div class="carrousel-element">
                <a href="productos.html"><img class="images" src="{{ asset('sources/data/mueble.png') }}"
                        alt="muebles"></a>
            </div>
        </div>
    </div>


    <!--//BLOQUE COOKIES-->
    <div class="cookiesms" id="cookie1">
        Esta web utiliza cookies, puedes ver nuestra <a class="polit" href="{{ route('cookies') }}">política de
            cookies</a>
        .Si continúas navegando estás aceptándola
        <button id="acept" onclick="controlcookies()">Aceptar</button>
        <div class="cookies2" onmouseover="document.getElementById('cookie1').style.bottom = '0px';">Política de cookies +
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script>
        //add to cart using localstorage
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
                            "seller_id": 1,
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
                        seller_id: response.seller_id,
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
            $(".modal-backdrop").remove(); // hide the overlay
                            $('body').removeClass("modal-open");
            toastr.success(message);
        }

        function displayErrors(errors) {
            toastr.error(errors);
        }

        function controlcookies() {
            // si variable no existe se crea (al clicar en Aceptar)
            localStorage.controlcookie = (localStorage.controlcookie || 0);

            localStorage.controlcookie++; // incrementamos cuenta de la cookie
            cookie1.style.display = 'none'; // Esconde la política de cookies
        }
        if (localStorage.controlcookie > 0) {
            const cookie1 = document.getElementById('cookie1');
            if (cookie1)
                cookie1.style.bottom = "-50px";
        }
    </script>
@endsection
