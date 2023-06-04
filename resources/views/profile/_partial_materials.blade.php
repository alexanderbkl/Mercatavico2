<table class="table" id="tableProducts">

<thead class="thead-dark">
<tr>
    <th scope="col">Id</th>
    <th scope="col">Nombre</th>
    <th scope="col">Editar/borrar</th>
</tr>
</thead>
<tbody>
@if(count($materials)>0)
    @foreach($materials as $material)
        <tr>
            <td>{{$material->id}}</td>
            <td>{{$material->name}}</td>
            <td>
                <button class="btn btn-success edit_material" data-toggle="modal" data-target="#modalEditMaterial" data-name="{{$material->name}}"
                        data-id="{{$material->id}}">Editar</button>
                <button class="btn btn-danger deleteMaterial"  data-id="{{$material->id}}"  data-toggle="modal" data-target="#confirmDeleteMaterialModal">Eliminar</button>

            </td>
        </tr>
    @endforeach
@else
    <p>No tienes materiales</p>

@endif

</tbody>
</table>

<!-- Edit material Modal -->
<div class="modal fade" id="modalEditMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editMaterial">
                    <div class="row" style="width: 100%">
                        <input type="hidden" class="form-control" name="material_id_edit" id="material_id_edit">

                        <div class="form-group col-12 col-md-6">

                            <div class="col-xs-6">
                                <label for="title"><h4>Nombre</h4></label>
                                <input type="text" class="form-control" name="material_name_edit" id="material_name_edit">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnUpdateMaterial" data-dismiss="modal">Guardar material</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirm delete material Modal -->
<div class="modal fade" id="confirmDeleteMaterialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <h5 class="modal-title" id="exampleModalLongTitle">¿Seguro que quieres eliminar este material?</h5>
            </div>
            <input type="hidden" name="material_id_delete_input">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="confirm_delete_material_btn" data-dismiss="modal">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>

    $('.edit_material').click((e)=>{

        $('input[name="material_id_edit"]').val(e.currentTarget.dataset.id)
        $('input[name="material_name_edit"]').val(e.currentTarget.dataset.name)
    })
    $('.deleteMaterial').click((e)=>{
        $('input[name="material_id_delete_input"]').val(e.currentTarget.dataset.id)
    })
    $('#confirm_delete_material_btn').click(()=> {
        let data = new FormData();
        data.append('material_id', $('input[name="material_id_delete_input"]').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('material.destroy')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {
                $('#contentMaterials').html(data.view)
                toastr.success(data.message);
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            }
        })
    })
    $('#btnUpdateMaterial').click(()=>{
        let data = new FormData();
        data.append('material_id', $('input[name="material_id_edit"]').val());
        data.append('material_name', $('input[name="material_name_edit"]').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{route('material.update')}}',
            type: 'post',
            contentType: false,
            processData: false,
            data: data,
            success: function (data) {
                $('#contentMaterials').html(data.view)
                toastr.success(data.message);
            },
            error: function (error) {
                toastr.error(error.responseJSON.message);
            }
        });

    })


</script>
