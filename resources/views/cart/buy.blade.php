@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12" style="text-align: center">
                <h1>Carrito de compra</h1>
            </div>
            <div class="col-sm-12 col-md-12" id="table_cart">
                @include('cart._partial_cart')
            </div>
        </div>
    </div>
@endsection
