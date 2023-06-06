@extends('layouts.main')
@section('content')
    <style>
        label {
            display: inline-block;
            margin-bottom: .1rem;
        }

        .card {
            text-align: center;
        }

        .contenedor_datos {
            margin-top: 50px;
            height: 50vh;
            overflow: auto;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8" style="margin:auto">
                <div class="card">
                    <div class="card-head" style="text-align: center">
                        <h1>Mis datos</h1>
                        @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="color:red;font-weight: bold">- {{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="POST" id="form_edit_user">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div class="labelform">
                                            <label for="name">Nombre y apellidos</label>
                                        </div>
                                        <div class="inputform">
                                            <input class="form-control text-center" type="text" id="name"
                                                name="name" placeholder=""
                                                value="{{ \Illuminate\Support\Facades\Auth::user()->name }}">
                                        </div>
                                        <ul class="errors"></ul>
                                    </div>
                                    <div class="form-group">
                                        <div class="labelform">
                                            <label for="email">Correo</label>
                                        </div>
                                        <div class="inputform">
                                            <input class="form-control" required type="text" id="email"
                                                name="email" value="{{ Auth::user()->email }}">
                                        </div>
                                        <ul class="errors"></ul>
                                    </div>
                                    <div class="form-group">
                                        <div class="labelform">
                                            <label for="password">Contraseña</label>
                                        </div>
                                        <div class="inputform">
                                            <input class="form-control" type="password" id="password"
                                                autocomplete="new-password" name="password" placeholder=""><br>
                                            <small>Dejar en blanco para mantener la misma</small>
                                        </div>
                                    </div>
                                    <div class="form-group d-inline-block">
                                        <div class="labelform">
                                            <label for="password_c">Confirmar contraseña</label>
                                        </div>
                                        <div class="inputform">
                                            <input class="form-control" type="password" id="password_c"
                                                autocomplete="new-password" name="password_confirmation"><br>
                                            <small>Dejar en blanco para mantener la misma</small>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div class="labelform">
                                            <fieldset>
                                                <legend>Datos de facturación</legend>
                                                <div class="labelform">
                                                    <label for="address">Direccion</label>
                                                </div>
                                                <div class="inputform form-group d-inline-block">
                                                    <input class="form-control text-center w-auto" required type="text"
                                                        id="address" name="address"
                                                        value="{{ Auth::user()->addressUser->address }}">
                                                </div>
                                                <!--using blade notation, for each ciudad in $ciudades create a select options-->
                                                <div class="labelform">
                                                    <label for="ciudad">Ciudad</label>
                                                </div>
                                                <div class="inputform form-group d-inline-block">
                                                    <select name="ciudad" class="form-control w-auto text-center"
                                                        id="ciudad">
                                                        @foreach ($ciudades as $ciudad)
                                                            <option value="{{ $ciudad->id }}"
                                                                @if (Auth::user()->addressUser && Auth::user()->addressUser->city_id == $ciudad->id) selected @endif>
                                                                {{ $ciudad->province }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="labelform ">
                                                    <label for="cp">Codigo postal</label>
                                                </div>
                                                <!--preferred payment method (paypal, tarjeta)-->
                                                <div class="inputform d-inline-block form-group">
                                                    <input class="form-control text-center w-auto" required type="text"
                                                        id="cp" name="cp"
                                                        value="{{ Auth::user()->addressUser ? Auth::user()->addressUser->cp : '' }}">
                                                </div>
                                                <div class="labelForm">
                                                    <label for="payment_method">Método de pago preferido</label>
                                                </div>
                                                <div class="inputForm  d-inline-block form-group">
                                                    <select class="form-control text-center w-auto" name="fav_pay"
                                                        id="fav_pay">
                                                        <option value="1"
                                                            @if (Auth::user()->buyer->fav_pay == 'paypal') selected @endif>PayPal
                                                        </option>
                                                        <option value="2"
                                                            @if (Auth::user()->buyer->fav_pay == 'tarjeta') selected @endif>Tarjeta
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="labelForm">
                                                    <label for="payment_method">Horario de entrega preferido</label>
                                                </div>
                                                <div class="inputForm d-inline-block form-group">
                                                    <select class="w-auto  text-center form-control"
                                                        name="shipping_preferences" id="shipping_preferences">
                                                        <option value="Mañana"
                                                            @if (Auth::user()->buyer->shipping_preferences == 'Mañana') selected @endif>Mañana
                                                        </option>
                                                        <option value="Tarde"
                                                            @if (Auth::user()->buyer->shipping_preferences == 'Tarde') selected @endif>Tarde
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="labelForm">
                                                    <label for="checkbox">Ofrecer servicio de reembolso</label>
                                                </div>
                                                <div class="form-check inputForm">
                                                    <fieldset>
                                                        <input class="form-check-input" type="checkbox" id="payback"
                                                            name="payback"
                                                            @if (Auth::user()->seller->payback == '1') checked @endif>
                                                    </fieldset>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <ul class="errors"></ul>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p>Mis puntos canjeables:
                                        <span style="font-size: 20px;font-weight: bold">
                                            {{ \Illuminate\Support\Facades\Auth::user()->credits }}
                                        </span>
                                    </p>
                                    <p>Mis puntos totales acumulados:
                                        <span style="font-size: 20px;font-weight: bold">
                                            {{ \Illuminate\Support\Facades\Auth::user()->seller->cred_total }}
                                        </span>
                                    </p>
                                    <p>Mi puntuación media:
                                        <span style="font-size: 20px;font-weight: bold">
                                            {{ \Illuminate\Support\Facades\Auth::user()->seller->calificate }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <br>
                            <ul class="errors"></ul>
                            <button id="btn_submit_user" class="btn btn-primary" type="button">Editar</button>
                        </form>

                    </div>
                    <div>
                        <p style="color:red"></p>
                    </div>
                </div>
            </div>
            <div class="col-12" style="text-align: center;margin-top: 50px">
                @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador')
                    <h1>Todos los productos</h1>
                @else
                    <h1>Mis productos</h1>
                    <h2>¡ Vende tus productos y gana la mitad del importe en puntos ! 1 punto = 1 euro</h2>
                @endif
                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddProducts">Añadir
                    producto
                </button>
                <br><br>
                <input type="search" class="form-control" placeholder="Buscar productos..." id="search_productos">
                <div id="contentProductos" class="contenedor_datos">
                    @include('profile._partial_mis_productos', $userProducts)
                </div>
            </div>
            @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador')
                <div class="col-12" style="text-align: center;margin-top: 50px">
                    <h1>Usuarios</h1>
                    <input type="search" class="form-control" placeholder="Buscar usuarios..." id="search_usuarios">
                    <div id="contentUsers" style="margin-top: 50px">
                        @include('profile._partial_usuarios', [
                            'usuarios' => $usuarios,
                            'ciudades' => $ciudades,
                        ])
                    </div>
                </div>

                <div class="col-12" style="text-align: center;margin-top: 50px">
                    <h1>Materiales</h1>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddMaterial">Añadir
                        material
                    </button>
                    <div id="contentMaterials" style="margin-top: 50px">
                        @include('profile._partial_materials', $materials)
                    </div>
                </div>
            @endif
            <div class="col-12" style="text-align: center;margin-top: 50px">
                @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador')
                    <h1>Todos los pedidos</h1>
                @else
                    <h1>Mis pedidos</h1>
                @endif
                <div id="contentPedidos" style="margin-top: 50px">
                    <table class="table" id="tableOrders">

                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Transaccion</th>
                                <th scope="col">Total</th>
                                <th scope="col">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pedidos) > 0)
                                @foreach ($pedidos as $pedido)
                                    <tr>
                                        <td>{{ $pedido->id }}</td>
                                        <td>{{ $pedido->user->name }}</td>
                                        <td>{{ $pedido->transaction }}</td>
                                        <td>{{ number_format($pedido->total, 2, ',', '.') }}€</td>
                                        <td>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <p>No hay pedidos</p>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12" style="text-align: center;margin-top: 50px">
                @if (\Illuminate\Support\Facades\Auth::user()->rol->name == 'administrador')
                    <h1>Calificaciones de todos los productos comprados</h1>
                @else
                    <h1>Todos mis productos comprados</h1>
                @endif
                <div id="contentPedidos" style="margin-top: 50px">
                    @include('profile._partial_mis_productos_comprados', [
                        'boughtProducts' => $boughtProducts,
                    ])
                </div>
            </div>
        </div>
    </div>

    <!--  Modal Add product -->
    <div class="modal fade" id="modalAddProducts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br />

                </div>
                <div class="modal-body">
                    <form id="addProduct">
                        <div class="row" style="width: 100%">

                            <div class="form-group col-12" style="text-align: center">
                                <img style="width:200px" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png"
                                    class="avatar img-circle img-thumbnail" alt="image">
                                <h6>Añade una foto</h6>
                                <input type="file" name="foto" accept="image/*"
                                    class="text-center center-block file-upload">
                            </div>
                            <div class="form-group col-12 col-md-6">

                                <div class="col-xs-6">
                                    <label for="title">
                                        <h4>Titulo</h4>
                                    </label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <div class="col-xs-6">
                                    <label for="last_name">
                                        <h4>Descripcion</h4>
                                    </label>
                                    <textarea type="text" class="form-control" name="descripcion"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">

                                <div class="col-xs-6">
                                    <label for="phone">
                                        <h4>Precio</h4>
                                    </label>
                                    <input type="number" required step="0.01" class="form-control" name="price">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <div class="col-xs-6">
                                    <label for="mobile">
                                        <h4>Stock</h4>
                                    </label>
                                    <input required type="number" step="1" class="form-control" name="stock">
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">

                                <div class="col-xs-6">
                                    <label for="email">
                                        <h4>Estado</h4>
                                    </label>
                                    <select class="form-control" name="status">
                                        <option value="Nuevo">Nuevo</option>
                                        <option value="Usado">Usado</option>
                                        <option value="Estropeado">Estropeado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <div class="col-xs-6">
                                    <label for="status">
                                        <h4>Materiales (múltiple)</h4>
                                    </label>
                                    <select class="form-control" id="inputSelectMateriales" name="materiales"
                                        multiple="multiple">
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnSaveProduct" data-dismiss="modal">Guardar
                        producto</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add material-->
    <div class="modal fade" id="modalAddMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMaterial">
                        <div class="row" style="width: 100%">
                            <div class="form-group col-12 col-md-6">
                                <div class="col-xs-6">
                                    <label for="title">
                                        <h4>Nombre</h4>
                                    </label>
                                    <input type="text" class="form-control" name="add_product_name"
                                        id="product_name">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnAddMaterial" data-dismiss="modal">Guardar
                        material</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        @if (session('deleteCart'))
            toastr.success('Carrito vaciado correctamente');
            //delete cart from localstorage
            localStorage.removeItem('cart');
        @endif
        $('#btn_submit_user').click(() => {
            let data = new FormData();
            data.append('name', $('input[name="name"]').val());
            data.append('email', $('input[name="email"]').val());
            data.append('password', $('input[name="password"]').val());
            data.append('password_confirmation', $('input[name="password_confirmation"]').val());
            data.append('address', $('input[name="address"]').val());
            data.append('ciudad', $('select[name="ciudad"]').val());
            data.append('cp', $('input[name="cp"]').val());
            data.append('fav_pay', $('select[name="fav_pay"]').val());
            data.append('shipping_preferences', $('select[name="shipping_preferences"]').val());
            //get payback checkbox value, if is checked, set 1, else set 0
            if ($('input[name="payback"]').is(':checked')) {
                data.append('payback', 1);
            } else {
                data.append('payback', 0);
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: '{{ route('profile.update') }}',
                type: 'post',
                contentType: false,
                processData: false,
                data: data,
                success: function(data) {
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        })

        var readURL = function(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.avatar').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".file-upload").on('change', function() {
            readURL(this);
        });
        $('#btnSaveProduct').click(() => {
            let data = new FormData();
            data.append('title', $('input[name="title"]').val());
            //check if foto is empty
            if ($('input[name="foto"]')[0].files[0] == undefined) {
                alert('Debes añadir una foto!!!');
                return;
            } else {
                data.append('foto', $('input[name="foto"]')[0].files[0]);
            }
            data.append('foto', $('input[name="foto"]')[0].files[0]);
            //check if descripcion is empty
            if ($('textarea[name="descripcion"]').val() == '') {
                alert('Debes añadir una descripción!!!');
                return;
            } else {
                data.append('descripcion', $('textarea[name="descripcion"]').val());
            }
            //check if price is empty
            if ($('input[name="price"]').val() == '') {
                alert('Debes añadir un precio!!!');
                return;
            } else {
                data.append('price', $('input[name="price"]').val());
            }
            //check if stock is empty
            if ($('input[name="stock"]').val() == '') {
                alert('Debes añadir un stock!!!');
                return;
            } else {
                data.append('stock', $('input[name="stock"]').val());
            }
            //check if status is empty
            if ($('select[name="status"]').val() == '') {
                alert('Debes añadir un estado!!!');
                return;
            } else {
                data.append('status', $('select[name="status"]').val());
            }
            //check if materiales is empty
            if ($('#inputSelectMateriales').val() == '') {
                alert('Debes seleccionar al menos un material!!!');
                return;
            } else {
                data.append('materiales', $('#inputSelectMateriales').val());
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '{{ route('product.store') }}',
                type: 'post',
                contentType: false,
                processData: false,
                data: data,
                success: function(data) {

                    $('#contentProductos').html(data.view)
                    $('#addProduct')[0].reset();
                    $('.img-thumbnail').attr('src', 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png')
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error);
                }
            });
        })

        $('#btnAddMaterial').click(() => {
            let data = new FormData();
            data.append('material_name', $('input[name="add_product_name"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '{{ route('add.material') }}',
                type: 'post',
                contentType: false,
                processData: false,
                data: data,
                success: function(data) {
                    $('#contentMaterials').html(data.view)
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });

        })
    </script>
@endsection
