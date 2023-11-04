@php
	use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ticket de cierre de caja</title>
	<link rel = "preconnect" href = "https://fonts.gstatic.com">
</head>
<body>

	<div class="container">
		<div class="container-fluid p-0">
		<div class="header">
				<div class="text-header">
						<h5 class="centrado mt-2">CIERRE DE CAJA</h5>
						<p class="small m-2 mt-1">Generado al: {{$hoy}}</p>
						<p class="small m-2 mt-1">Usuario: {{$usuario}}</p>
				</div>
				<hr style="width: 90%; margin:3px 5px; padding-left: 5px; ">
				<p class="small m-2 mt-1">Cuadre del día <strong>14/05/2023</strong></p>
				<p class="small m-2 mt-1">==== ENTRADAS ======</p>
				@foreach($sumas as $key=> $valor)
					<p class="small m-2 mt-1">{{$key}}: S/ <strong>{{number_format($valor,2)}}</strong></p>
				@endforeach
				<p class="small m-2 mt-1">==== Salidas ======</p>
				@foreach($sumasSalidas as $key=> $valor)
					<p class="small m-2 mt-1">{{$key}}: S/ <strong>{{number_format($valor,2)}}</strong></p>
				@endforeach
				<p class="small m-2 mt-1">==== Anulados ======</p>
				@foreach($sumasAnulados as $key=> $valor)
					<p class="small m-2 mt-1">{{$key}}: S/ <strong>{{number_format($valor,2)}}</strong></p>
				@endforeach

				<p class="small m-2 mt-1">==========</p>
				<p class="small m-2 mt-1">Total entradas: S/ <strong>{{number_format($total, 2)}}</strong></p>
				<p class="small m-2 mt-1">Total salidas: S/ <strong>{{number_format($totalSalidas, 2)}}</strong></p>
				<p class="small m-2 mt-1">Total anulados: S/ <strong>{{number_format($totalAnulados, 2)}}</strong></p>
		</div>
		</div>
		<div class="mt-2"></div>
		<div id="espacio"></div>
		<p class="small m-2 centrado">Firma de conformidad</p>
		<p class="small m-2 centrado">Nombre:</p>

	</div>
	
<style>
*{
	margin: 0;
	padding: 0;
}
@font-face{
	font-family:'Roboto', sans-serif;
	src: url('fuentes/Roboto-Light.ttf');
	font-weight: normal;
	font-style: normal;
}
body{
	background-color:white;
	font-family: 'Roboto', sans-serif;
}
h1,h2,h3,h4{font-size: 11px;}
p{font-size: 11px;}
.centrado{text-align: center;}
.m-2{margin-left: 15px;}
.mt-2{margin-top: 10px;}
.mt-1{margin-top: 3px;}
#espacio{width: 150px; height: 80px; border: 1px solid black; margin: 0 auto;}

</style>
</body>
</html>