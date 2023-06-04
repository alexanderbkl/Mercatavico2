@extends("layouts.main")
@section("content")
    <style>
        .controlinput {
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(197, 193, 192, 0) inset;
            color: #6d6665;
            margin: 0.0em 0;
            border: 1px solid #c5c1c0;
            padding: 0.6em 0.6em;
            transition: box-shadow 300ms ease-out;
        }
        .control-label--showpass {
            top: 62px;
            left: 100%;
        }
        .structurel input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .structurel input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .usertitle{
            color: black;
        }
    </style>
    <div class="structurel">
        <h1 class="usertitle">Mercatavico</h1>
        <h3 class="usertitle">Login</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="fields">
                <div>
                    <label class="label" for="name">Email</label>
                    <input class="controlinput" type="email" id="email" name="email" maxlength="32"
                           placeholder=" " autocomplete="off" autocapitalize="off"
                           autocorrect="off" required>
                    <ul class="errors"></ul>
                </div>
                <div>

                    <label class="control-label control-label--showpass" for="show-pass">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="32" height="32"
                             class="svg-toggle-pass" title="Clave seguridad">
                            <path d="M24,9A23.654,23.654,0,0,0,2,24a23.633,23.633,0,0,0,44,0A23.643,23.643,0,0,0,24,9Zm0,25A10,10,0,1,1,34,24,10,10,0,0,1,24,34Zm0-16a6,6,0,1,0,6,6A6,6,0,0,0,24,18Z"/>
                            <rect x="20.133" y="2.117" height="44" transform="translate(23.536 -8.587) rotate(45)"
                                  class="close-eye"/>
                            <rect x="22" y="3.984" width="4" height="44" transform="translate(25.403 -9.36) rotate(45)"
                                  style="fill:#fff" class="close-eye"/>
                        </svg>
                    </label>
                    <label class="label" for="pass">Clave</label>
                    <input class="controlinput controlinput--pass" type="password" id="pass" name="password" maxlength="32"
                           pattern="[A-Za-z0-9]+" placeholder="" autocomplete="off" autocapitalize="off"
                           autocorrect="off" required>
                    <ul class="errors"></ul>
                </div>
                <ul class="errors"></ul>
                <br>
                <input class="send"  type="submit" value="Conectarse">
                <br>
                <a href="{{ route('register') }}">¿No tienes cuenta? ¡Registrate!</a>
            </div>
        </form>
    </div>

@endsection
@section('javascript')
    <script>
        $('.control-label--showpass').click((e)=>{

            if($('input[name="password"]').attr('type' )=='text'){
                $('input[name="password"]').attr('type','password')
            }else{
                $('input[name="password"]').attr('type','text')
            }
        })
    </script>

@endsection
