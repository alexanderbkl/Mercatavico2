<!-- Header -->
<header width="500" height="600">
    <a href="{{ route('home') }}"><img src="{{ asset('sources/data/logo_blanco.png') }}" class="logo"></a>

    <!-- Icono Hamburguesa -->
    <input class="lateral-menu" type="checkbox" id="lateral-menu">
    <label class="hamb" for="lateral-menu">
        <span class="hamb-line"></span>
    </label>
    <!-- Menu -->
    <nav class="nav">
        <ul class="menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('aboutus') }}">Acerca de</a> </li>
            <li><a href="{{ route('contact.index') }}">Contacto</a></li>
            <li><a href="{{ route('products.index') }}">Productos</a></li>
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
            @endguest
            @auth
                <li><a href="{{ route('profile.edit') }}">Perfil</a></li>
                @if (\Illuminate\Support\Facades\Auth::user()->rol_id == 5)
                    <li><a href="">Admin</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="#"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            Cerrar sesión
                        </a>
                    </form>
                </li>
            @endauth
            <li id="basket"><a href="{{ route('cart.index') }}">
                    <img src="{{ asset('sources/data/cesta.png') }}" class="logo" alt="carrito">
                    <span class="badge badge-danger" id="numItemsCart"></span></a>
            </li>
        </ul>
    </nav>

</header>

@section('javascript')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            //get number of items in cart from local storage
            //cart value looks like this:
            /*
            {"2":{"id":2,"title":"product2","price":"20.00","stock":20,"description":"description2","foto":"Z2PIAt3yqKtm3fs1678866576jpg","status":"Usado","user_id":2,"created_at":"2023-06-04T01:13:46.000000Z","updated_at":"2023-06-04T01:13:46.000000Z","quantity":4},"3":{"id":3,"title":"product3","price":"30.00","stock":30,"description":"description3","foto":"Z2PIAt3yqKtm3fs1678866576jpg","status":"Estropeado","user_id":3,"created_at":"2023-06-04T01:13:46.000000Z","updated_at":"2023-06-04T01:13:46.000000Z","quantity":2}}
            */
            var cart = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : {};
            var numItemsCart = Object.keys(cart).length;
            //set number of items in cart in the badge
            $('#numItemsCart').text(numItemsCart);
        });
    </script>
