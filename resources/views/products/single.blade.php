@extends("layouts.main")
@section("content")
    <style>
        p {
            margin: unset;
        }
        .materials_list li{
            list-style: disclosure-closed;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img style="width: 100%" src="{{asset('storage/productsImages/'.$producto->foto)}}">
            </div>
            <div class="col-12 col-md-6">
                <h1>{{$producto->title}}</h1>
                <p>{{$producto->description}}</p>

                @if($producto->materiales)
                <p>Materiales:</p>
                    <ul style="margin-left: 15px" class="materials_list">
                        @foreach($producto->materiales as $materialPivot)
                           <li>{{$materialPivot->material->name}}</li>
                        @endforeach

                    </ul>
                @endif
                <p style="font-weight: bold;font-size: 16px">Precio: {{$producto->price}}€</p>

                @auth
                    @if($producto->user->id!=\Illuminate\Support\Facades\Auth::id())
                    <button type="button" class="btn btn-primary addCartBtn" data-product_id="{{$producto->id}}"><i class="fa fa-plus"></i> Añadir al carrito</button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <script>
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
