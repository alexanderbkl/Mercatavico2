<table class="table" id="tableProducts">

<thead class="thead-dark">
<tr>
    <th scope="col">Imagen</th>
    <th scope="col">Título</th>
    <th scope="col">Descripción</th>
    <th scope="col">Precio</th>
    <th scope="col">Stock</th>
    <th scope="col">Estado</th>
    <th scope="col">Usuario</th>
    <th scope="col">Materiales</th>
    <th scope="col">Editar/Borrar</th>
</tr>
</thead>
<tbody id="content_products_table">
@if(count($userProducts)>0)

    @foreach($userProducts as $producto)
        <tr class="product_item_list" data-product_id="{{$producto->id}}"
            data-title="{{$producto->title}}" data-description="{{$producto->description}}" data-estado="{{$producto->state}}">
            <th scope="row">
                <img style="width: 90px" src="{{asset('storage/productsImages/'.$producto->foto)}}">
            </th>
            <td>{{$producto->title}}</td>
            <td>{{\Illuminate\Support\Str::limit($producto->description,50)}}</td>
            <td>{{$producto->price}}€</td>
            <td>{{$producto->stock}} unidades</td>
            <td>{{$producto->state}}</td>
            <td>{{$producto->user->name.' - '.$producto->user->email}}</td>
            <td>
                @if($producto->materiales)
                    <ul>
                    @foreach($producto->materiales as $materialPivot)
                        <li>{{$materialPivot->material->name}}</li>
                    @endforeach
                    </ul>
                @endif
            </td>
            <td>
                @php
                        $arrayMateriales = [];
                       if($producto->materiales){

                           foreach($producto->materiales as $material){
                               array_push($arrayMateriales,$material->material_id);
                           }
                           $arrayMateriales = implode(",",$arrayMateriales);
                       }else{
                           $arrayMateriales="";
                       }
                @endphp
                <button class="btn btn-success edit_product" data-toggle="modal" data-target="#modalEditProducts" data-foto="{{$producto->foto}}"
                        data-id="{{$producto->id}}" data-materiales="{{$arrayMateriales}}" data-title="{{$producto->title}}" data-description="{{$producto->description}}"
                        data-price="{{$producto->price}}" data-stock="{{$producto->stock}}" data-state="{{$producto->state}}" >Editar</button>
                <button class="btn btn-danger deleteProduct"  data-id="{{$producto->id}}"  data-toggle="modal" data-target="#confirmDeleteModal">Eliminar</button>
            </td>
        </tr>
    @endforeach
@else
    <p>No tienes produtos</p>

@endif

</tbody>
</table>

<!-- Edit product Modal -->
<div class="modal fade" id="modalEditProducts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProduct">
                    <div class="row" style="width: 100%">
                        <input type="hidden" class="form-control" name="product_id" id="product_id">

                        <div class="form-group col-12" style="text-align: center">
                            <img style="width:250px" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png"
                                 class="avatar img-circle img-thumbnail_edit" alt="image">
                            <h6>Añade una foto</h6>
                            <input id="foto_edit_input" type="file" name="foto_edit" accept="image/*" class="text-center center-block foto_edit">
                        </div>
                        <div class="form-group col-12 col-md-6">

                            <div class="col-xs-6">
                                <label for="title"><h4>Titulo</h4></label>
                                <input type="text" class="form-control" name="title_edit" id="title">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="last_name"><h4>Descripcion</h4></label>
                                <textarea type="text" class="form-control" name="descripcion_edit"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">

                            <div class="col-xs-6">
                                <label for="phone"><h4>Precio</h4></label>
                                <input type="number" required step="0.01" class="form-control" name="price_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="mobile"><h4>Stock</h4></label>
                                <input  required type="number" step="1" class="form-control" name="stock_edit">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="state"><h4>Estado</h4></label>
                                <select class="form-control" name="state_edit">
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="Usado">Usado</option>
                                    <option value="Estropeado">Estropeado</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <div class="col-xs-6">
                                <label for="state"><h4>Materiales (múltiple)</h4></label>
                                <select class="form-control" id="inputSelectMaterialesEdit" name="materiales" multiple="multiple">
                                    @foreach(\App\Models\Material::all() as $material)
                                        <option value="{{$material->id}}">{{$material->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnUpdateProduct" data-dismiss="modal">Guardar producto</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirm delte Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <h5 class="modal-title" id="exampleModalLongTitle">¿Seguro que quieres eliminar este producto?</h5>
            </div>
            <input type="hidden" name="product_id_delete_input">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary confirm_delete_btn" data-dismiss="modal">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    var productList = [];
    $('.product_item_list').each(function (index) {
        productList.push($(this)[0]);
    })
    $('#search_productos').keyup((e) => {

        $('#content_products_table').html('')
        var productResult = productList.filter(product =>
            product.dataset.title.toLowerCase().includes(e.currentTarget.value.toLowerCase()) ||
            product.dataset.estado.toLowerCase().includes(e.currentTarget.value.toLowerCase()) ||
            product.dataset.description.toLowerCase().includes(e.currentTarget.value.toLowerCase()))
        for (product of productResult) {
            $('#content_products_table').append(product)
        }
    })

    $('.edit_product').click((e)=>{
        $('.img-thumbnail_edit').attr('src','{{asset('storage/productsImages/')}}/'+e.currentTarget.dataset.foto)
        $('input[name="product_id"]').val(e.currentTarget.dataset.id)
        $('input[name="title_edit"]').val(e.currentTarget.dataset.title);
        $('textarea[name="descripcion_edit"]').val(e.currentTarget.dataset.description);
        $('input[name="price_edit"]').val(e.currentTarget.dataset.price);
        $('input[name="stock_edit"]').val(e.currentTarget.dataset.stock);
        $('select[name="state_edit"]').val(e.currentTarget.dataset.state);
        $('#inputSelectMaterialesEdit').val(e.currentTarget.dataset.materiales.split(','));
    })

    $('#btnUpdateProduct').click((e)=>{
        let data = new FormData();
        data.append('product_id', $('input[name="product_id"]').val());
        data.append('title', $('input[name="title_edit"]').val());
        data.append('foto', $('input[name="foto_edit"]')[0].files[0]);
        data.append('descripcion', $('textarea[name="descripcion_edit"]').val());
        data.append('price', $('input[name="price_edit"]').val());
        data.append('stock', $('input[name="stock_edit"]').val());
        data.append('state', $('select[name="state_edit"]').val());
        data.append('materiales', $('#inputSelectMaterialesEdit').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('product.update')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {

                $('#contentProductos').html(data.view)
                $('#editProduct')[0].reset();
                $('.img-thumbnail').attr('src','http://ssl.gstatic.com/accounts/ui/avatar_2x.png')
                toastr.success(data.message);
            },
            error: function (error) {

                toastr.error(error);
            }
        });
    })
    $('.deleteProduct').click((e)=>{
        $('input[name="product_id_delete_input"]').val(e.currentTarget.dataset.id)
    })
    $('.confirm_delete_btn').click(()=> {
        let data = new FormData();
        data.append('product_id', $('input[name="product_id_delete_input"]').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('product.destroy')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {
                $('#contentProductos').html(data.view)
                $('input[name="product_id_delete_input"]').val('')
                $('.modal-backdrop').remove()
                $('body').removeClass('modal-open')
                toastr.success(data.message);
            },
            error: function (error) {
                toastr.error(error);
            }
        })
    })

    var readURLEdit = function (input) {
        if (input.files && input.files[0]) {
            var readerEdit = new FileReader();

            readerEdit.onload = function (e) {
                $('.img-thumbnail_edit').attr('src', e.target.result);
            }

            readerEdit.readAsDataURL(input.files[0]);
        }
    }
    $("#foto_edit_input").on('change', function () {

        readURLEdit(this);
    });

</script>
