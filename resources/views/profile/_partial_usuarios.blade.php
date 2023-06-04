<table class="table" id="tableProducts">

    <thead class="thead-dark">
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Nombre y apellidos</th>
        <th scope="col">Email</th>
        <th scope="col">Créditos</th>
        <th scope="col">Rol</th>
        <th scope="col">Dirección</th>
        <th scope="col">Ciudad</th>
        <th scope="col">Código postal</th>
        <th scope="col">Editar/Borrar/Administrador</th>
    </tr>
    </thead>
    <tbody id="content_users_table">
    @if(count($usuarios)>0)

        @foreach($usuarios as $usuario)
            <tr class="user_item_list" data-user_id="{{$usuario->id}}" data-name="{{$usuario->name}}" data-email="{{$usuario->email}}">
                <td>{{$usuario->id}}</td>
                <td>{{$usuario->name}}</td>
                <td>{{$usuario->email}}</td>
                <td>{{$usuario->credits}}</td>
                <td>{{$usuario->rol->name}}</td>
                <td>{{$usuario->addressUser ?  $usuario->addressUser->address : ''}}</td>
                <td>{{$usuario->addressUser ? $usuario->addressUser->city : ''}}</td>
                <td>{{$usuario->addressUser ? $usuario->addressUser->cp : ''}}</td>
                <td>
                    @if($usuario->id!=\Illuminate\Support\Facades\Auth::id())
                        <button class="btn btn-success edit_user" data-toggle="modal" data-target="#modalEditUsers"
                                data-name="{{$usuario->name}}"
                                data-id="{{$usuario->id}}" data-email="{{$usuario->email}}"
                                data-credits="{{$usuario->credits}}"
                                data-rol="{{$usuario->rol->name}}"
                                data-address="{{$usuario->addressUser ? $usuario->addressUser->address : ''}}"
                                data-city="{{$usuario->addressUser ? $usuario->addressUser->city : ''}}"
                                data-cp="{{$usuario->addressUser ? $usuario->addressUser->cp : ''}}"
                        >Editar
                        </button>
                        <button class="btn btn-danger deleteUser" data-id="{{$usuario->id}}" data-toggle="modal"
                                data-target="#confirmDeleteUserModal">Eliminar
                        </button>
                        @if($usuario->rol->name=="usuario_registrado")
                            <button class="btn btn-warning btnSetAdmin" style="margin-top: 10px"
                                    data-id="{{$usuario->id}}">Convertir Administrador
                            </button>
                        @else
                            <button class="btn btn-warning btnSetAdmin" style="margin-top: 10px"
                                    data-id="{{$usuario->id}}">Eliminar rol Administrador
                            </button>
                        @endif

                    @endif

                </td>
            </tr>
        @endforeach
    @else
        <p>No hay usuarios</p>

    @endif

    </tbody>
</table>

<!-- Edit user Modal -->
<div class="modal fade" id="modalEditUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProduct">
                    <div class="row" style="width: 100%">
                        <input type="hidden" class="form-control" name="user_id" id="user_id">

                        <div class="form-group col-12 col-md-6">

                            <div class="col-xs-6">
                                <label for="title"><h4>Nombre y apellidos</h4></label>
                                <input type="text" class="form-control" name="name_edit" id="name_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="last_name"><h4>Email</h4></label>
                                <input type="text" class="form-control" name="email_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="phone"><h4>Créditos</h4></label>
                                <input type="number" required step="1" class="form-control" name="credits_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="mobile"><h4>Driección</h4></label>
                                <input required type="text" class="form-control" name="address_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="mobile"><h4>Ciudad</h4></label>
                                <input required type="text" class="form-control" name="city_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="mobile"><h4>Código postal</h4></label>
                                <input required type="text" class="form-control" name="cp_edit">
                            </div>
                        </div>

                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnUpdateUser" data-dismiss="modal">Guardar usuario
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Confirm delete User Modal -->
<div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="exampleModalLongTitle">¿Seguro que quieres eliminar este usuario?</h5>
            </div>
            <input type="hidden" name="user_id_delete_input">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary confirm_delete_user" data-dismiss="modal">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    var userList = [];
    $('.user_item_list').each(function (index) {
        userList.push($(this)[0]);
    })
    $('#search_usuarios').keyup((e) => {

        $('#content_users_table').html('')
        var userResult = userList.filter(user =>
            user.dataset.name.toLowerCase().includes(e.currentTarget.value.toLowerCase()) ||
            user.dataset.email.toLowerCase().includes(e.currentTarget.value.toLowerCase()))
        for (user of userResult) {

            $('#content_users_table').append(user)
        }
    })

    $('.edit_user').click((e) => {
        $('input[name="user_id"]').val(e.currentTarget.dataset.id)
        $('input[name="name_edit"]').val(e.currentTarget.dataset.name);
        $('input[name="email_edit"]').val(e.currentTarget.dataset.email);
        $('input[name="credits_edit"]').val(e.currentTarget.dataset.credits);
        $('input[name="address_edit"]').val(e.currentTarget.dataset.address);
        $('input[name="city_edit"]').val(e.currentTarget.dataset.city);
        $('input[name="cp_edit"]').val(e.currentTarget.dataset.cp);
    })

    $('.deleteUser').click((e) => {
        $('input[name="user_id_delete_input"]').val(e.currentTarget.dataset.id)
    })

    $('.confirm_delete_user').click(() => {
        let data = new FormData();
        data.append('user_id', $('input[name="user_id_delete_input"]').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('user.destroy')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {
                toastr.success(data.message);
                $('#contentUsers').html(data.view)
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            }
        })
    })

    $('.btnSetAdmin').click((e) => {
        let data = new FormData();
        data.append('user_id', e.currentTarget.dataset.id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('change.rol')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {
                toastr.success(data.message);
                $('#contentUsers').html(data.view)
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            }
        });
    })


    $('#btnUpdateUser').click(() => {

        let data = new FormData();
        data.append('user_id', $('input[name="user_id"]').val());
        data.append('name', $('input[name="name_edit"]').val());
        data.append('email', $('input[name="email_edit"]').val());
        data.append('address', $('input[name="address_edit"]').val());
        data.append('city', $('input[name="city_edit"]').val());
        data.append('cp', $('input[name="cp_edit"]').val());

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('user.update')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {

                $('#contentUsers').html(data.view)
                $('#editProduct')[0].reset();
                $('.img-thumbnail').attr('src', '')
                $('.modal-backdrop').remove()

                toastr.success(data.message);
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            }
        });
    })


</script>
