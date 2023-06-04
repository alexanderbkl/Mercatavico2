<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <img class="img-responsive" style="width:25%" src="{{asset('sources/data/logo_blanco.png')}}">
        </div>
        <div class="col-md-12">
            <h3>Has recibido una nueva solicitud de contacto.</h3><br>
            <h4>Datos del contacto:</h4>
            <p>
                Nombre: <span>{{$datos['name'] }}</span>
            </p>
            <p>
                Email: <a href="mailto:{{ $datos['email'] }}"><span>{{ $datos['email'] }}</span></a>
            </p>
            <p>
                Teléfono: <span>{{ $datos['phone'] }}</span>
            </p>
            <p>
                Mensaje:  <span>{{ $contenido }}</span>
            </p>
        </div>
    </div>
</div>
</body>
</html>
