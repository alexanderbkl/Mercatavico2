@extends("layouts.main")
@section("content")
    <style>
        p {
            margin: unset;
        }
    </style>
    <h1 class="title titlemargin">ENCUENTRA TU RELIQUIA</h1>
    <h2 class="title">Envíos gratis a cualquier punto de la península</h2>
    <div class="container">
        <div class="row">
            <div class="col-6" style="margin: auto">
                <div class="row">
                    <div class="form-group">
                        <label for="pmin">Precio mínimo</label>
                        <input class="form-control" type="number" step="0.01"  id="pmin" name="pmin" min="0" placeholder="min ...">
                    </div>

                    <div class="form-group">
                        <label for="pmax">Precio máximo</label>
                        <input class="form-control" type="number" step="0.01" id="pmax" name="pmax" min="0" placeholder="max ...">
                    </div>
                    <div class="form-group">
                        <label for="pmax">Estado</label>
                        <select class="form-control" name="state" id="state">
                            <option value="">Todos</option>
                            <option value="nuevo">Nuevo</option>
                            <option value="usado">Usado</option>
                            <option value="estropeado">Estropeado</option>
                        </select>
                    </div>

                </div>
                <div class="col-12" style="text-align: center">
                    <button class="btn btn-success" id="btnFilter">Filtrar</button>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <div class="row" id="content_products">
           @include('products._partial_productos',$productos)
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $('#btnFilter').click((e) => {
            $('#content_products').html('')
            let estado = $('#state').val().toLowerCase();
            let pmin = $('#pmin').val() ? $('#pmin').val() : 0;
            let pmax = $('#pmax').val() ? $('#pmax').val() : 999999999;
            let data = new FormData();
            data.append('estado',estado);
            data.append('pmin',pmin);
            data.append('pmax',pmax);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                url: '{{route('product.filter')}}',
                type: 'post',
                contentType: false,
                processData: false,
                data: data,
                success: function (data) {
                    $('#content_products').html(data.view)
                },
                error: function (error) {
                    //toastr.error(error.responseJSON.message);
                }
            });
        })

        $('.addCartBtn').click((e)=>{
            let product_id = e.currentTarget.dataset.product_id;
            let url = '{{ route("cart.add", ":product_id") }}';
            url = url.replace(':product_id', product_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: url,
                type: 'get',
                success: function (data) {
                    $('#numItemsCart').text(data.numItems)
                    toastr.success(data.message);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        })
    </script>

@endsection
