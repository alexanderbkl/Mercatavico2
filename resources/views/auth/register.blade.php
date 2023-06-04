@extends('layouts.main')
@section('late_head')
    {!! RecaptchaV3::initJs() !!}
@endsection
@section('content')
    <style>
        .structurel label {
            padding: 0px 12px 0px 0;
            display: inline-block;
        }

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
            top: 50px;
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

        .usertitle {
            color: black;
        }

        .control-label--showpass-c {
            top: 50px;
            left: 92%;
            position: relative;
        }
    </style>
    <div class="structurel">
        <h1 class="usertitle">Mercatavico</h1>
        <h3 class="usertitle">Registro</h3>
        <!--Print the entire errors object-->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    <!--Print all errors-->
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <!--Print the first error-->
                <p>{{ $errors->first('name') }}</p>
            </div>
        @endif
        @if ($errors->any())
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        @endif
        <div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div id="register-component">
                    <div class="fields">

                        <div>
                            <register-component></register-component>
                        </div>

                    </div>


                    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                        {!! RecaptchaV3::field('register') !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>Error:
                                    {{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <ul class="errors"></ul>
                <br>
                <input class="send" type="submit" value="Registrarse">
                <br>
                <a href="{{ route('login') }}">¿Ya tienes una cuenta?</a>
        </div>
        </form>
    </div>
    </div>

@endsection
