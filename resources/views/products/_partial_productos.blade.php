@foreach($productos as $producto)
    <div class="col-12 col-md-4 product_item_list" style="margin-top: 40px"
         data-price="{{$producto->price}}"
         data-title="{{$producto->title}}" data-estado="{{$producto->state}}">

        <div class="card" style="width: 18rem;">
            <div class="card-head">
                <img style="width:100%;height: 200px;object-fit: cover" src="{{asset('storage/productsImages/'.$producto->foto)}}">
            </div>
            <div class="card-body" style="text-align: start">
                <h3>{{$producto->title}}</h3>
                <p>{{\Illuminate\Support\Str::limit($producto->description,100)}}</p>
                <p>{{$producto->price}}€</p>
                <p>{{$producto->stock}} de stock</p>
                <p>Estado: {{$producto->state}}</p>
            </div>
            <div class="card-footer" style="text-align: center">
                <a class="btn btn-success" href="{{route('product.show',$producto->id)}}">Ver producto</a>
                @auth
                    @if($producto->user->id!=\Illuminate\Support\Facades\Auth::id())
                        <button type="button" class="btn btn-primary addCartBtn" data-product_id="{{$producto->id}}" ><i class="fa fa-plus"></i> Añadir al carrito</button>
                    @endif
                @endauth
            </div>

        </div>
    </div>
@endforeach
