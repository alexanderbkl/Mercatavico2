@extends('layouts.main')



@section('content')
    <style>
        p {
            margin: unset;
        }

        .materials_list li {
            list-style: disclosure-closed;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img style="width: 100%" src="{{ asset('storage/productsImages/' . $producto->foto) }}">
            </div>
            <div class="col-12 col-md-6">
                <h1>{{ $producto->title }}</h1>
                <p>{{ $producto->description }}</p>

                @if ($producto->materiales)
                    <p>Materiales:</p>
                    <ul style="margin-left: 15px" class="materials_list">
                        @foreach ($producto->materiales as $materialPivot)
                            <li>{{ $materialPivot->material->name }}</li>
                        @endforeach

                    </ul>
                @endif
                @if ($producto->stock)
                    <p>Stock: {{ $producto->stock }}</p>
                @endif
                <p style="font-weight: bold;font-size: 16px">Precio: {{ $producto->price }}€</p>
                <p>Publicado por: {{ $producto->user->name }}</p>
                <p>Dirección:</p>
                <p>{{ $producto->user->addressUser->address . ' ' . $producto->user->addressUser->city->province . ' ' . $producto->user->addressUser->cp }}
                <div id="mapid" style="height: 180px;"></div>

                </p>
                @auth
                    @if ($producto->user->id != \Illuminate\Support\Facades\Auth::id())
                        <button type="button" class="btn btn-primary addCartBtn" data-product_id="{{ $producto->id }}"><i
                                class="fa fa-plus"></i> Añadir al carrito</button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet-src.js"></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet"></script>

    <!-- Esri Leaflet Geocoder -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css" />
    <script src="https://unpkg.com/esri-leaflet-geocoder"></script>

    <script>
        $('.addCartBtn').click((e) => {
            let product_id = e.currentTarget.dataset.product_id;
            let url = '{{ route('cart.add', ':product_id') }}';
            url = url.replace(':product_id', product_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: url,
                type: 'get',
                success: function(data) {
                    $('#numItemsCart').text(data.numItems)
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        })










        var address = "{{ $producto->user->addressUser->address }}";
        var city = "{{ $producto->user->addressUser->city->province }}";
        var postalCode = "{{ $producto->user->addressUser->cp }}";

        var fullAddress = address + ", " + city + ", " + postalCode;

        var map = L.map('mapid').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var geocodeService = L.esri.Geocoding.geocodeService({
            apikey: 'AAPKc507468e92a9497f95df17dc2881db6bKho4FvJSgeTraGh6966lg0_hwzdGpG4CXBVx7gTIYa9aEjao4MENdHAJb7Lg1XM7'
        });

        geocodeService.geocode().address(fullAddress).run(function(error, response) {
            if (error) {
                alert("error")
                console.log(error)
                return;
            }

            // Use the first result
            if (response.results && response.results.length > 0) {
                var firstResult = response.results[0];
                var latlng = firstResult.latlng;
                var address = firstResult.properties.LongLabel;

                map.setView(latlng, 13);

                L.marker(latlng).addTo(map)
                    .bindPopup(address)
                    .openPopup();
            }
        });
    </script>
@endsection
