<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Triaje</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	
	<style>
		 @font-face{
            font-family:'Roboto', sans-serif;
            src: url('fuentes/Roboto-Light.ttf');
            font-weight: normal;
            font-style: normal;
        }
		* {
			margin: 0px;
			padding: 10px;
		}

		body {
			font-family: 'Roboto', sans-serif;
			margin: auto;

		}
		table{
			width: 100%;
			border-collapse: collapse;
		}
		tr span{font-weight: normal;}
		thead{font-size: 0.9rem;}
		tbody{font-size: 0.8rem;}
		th, td{border: 1px solid #000; padding: 3px;}
		.text-center{ text-align: center; }
		.text-end{ text-align: right; }
		.px-3{padding-right: 3rem;}
		.w-20{width: 20%;}
		.w-40{width: 40%;}
		.w-50{width: 50%;}
		.w-60{width: 60%;}
	</style>
</head>

<body>
	@php
	use Carbon\Carbon;
	function romanos($numero){
		switch($numero){
			case '1': return 'I'; break;
			case '2': return 'II'; break;
			case '3': return 'II'; break;
			case '4': return 'IV'; break;
			case '5': return 'V'; break;
			default: break;
		}
	}
	@endphp
	<h4 class="text-center" style="margin-bottom:0px; padding-bottom:0px;">FICHA DE TRIAJE N° {{str_pad($triaje->id, 4, '0', STR_PAD_LEFT)}}</h4>
	<table>
		<thead>
			<tr>
				<th colspan=2>DATOS DE AFILIACIÓN</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="w-40"><strong>Apellidos y nombres</strong></td>
				<td class="w-60"> <span>{{$paciente->name}}</span></td>
			</tr>
			<tr>
				<td class="w-40"><strong>D.N.I.</strong></td>
				<td class="w-60"><span>{{$paciente->dni}}</span></td>
			</tr>
			<tr>
				<td class="w-40"><strong>Género</strong></td>
				<td class="w-60">
					<span>
						@if($paciente->gender==0)
							Femenino
						@elseif($paciente->gender==1)
							Masculino
						@elseif($paciente->gender==2)
							LGTB+
						@else
							No indica
						@endif
					</span>
				</td>
			</tr>
			<tr>
				<td class="w-40"><strong>Edad</strong></td>
				<td class="w-60"><span>
					@php
					$fecha_nacimiento = Carbon::parse( $paciente->birth_date ?? Carbon::now() );
					$edad = $fecha_nacimiento->diffInYears(Carbon::now());
					echo "$edad años";
					@endphp
				</span></td>
			</tr>
			<tr>
				<td class="w-40"><strong>Fecha de nacimiento</strong></td>
				<td class="w-60"><span>
					@php
					$fecha = Carbon::parse( $paciente->birth_date );
					echo $fecha->format('d/m/Y');
					@endphp
				</span></td>
			</tr>
			<tr>
				<td class="w-40"><strong>Ocupación</strong></td>
				<td class="w-60"><span>{{$paciente->occupation ?? '-' }}</span></td>
			</tr>
			<tr>
				<td class="w-20"><strong>Fecha y hora de la atención</strong></td>
				<td class="w-20"><span>
				@php
					$fecha = Carbon::parse( $triaje->fecha );
					echo $fecha->format('d/m/Y h:m a');
				@endphp
				</span></td>
			</tr>
			
			<tr>
				<td class="w-20"><strong>Celular</strong></td>
				<td class="w-20"><span>{{$paciente->phone?? "" }}</span></td>
			</tr>
			<tr>
				<td class="w-20"><strong>Celular del acompañante</strong></td>
				<td class="w-20"><span> {{$apoderado->phone??''}} {{$apoderado->name ?? '-'}}</span></td>
			</tr>
			
		</tbody>
	</table>
	<p class="mb-0"><small>Frecuencias vitales</small></p>
	<table style="margin-top:0px;">
		<tbody>
			<tr>
				<th style="border-top:1px solid black">FC</th>
				<th style="border-top:1px solid black">FR</th>
				<th style="border-top:1px solid black">PA</th>
				<th style="border-top:1px solid black">T</th>
			</tr>
			<tr>
				<td><span>{{$triaje->fc}}</span></td>
				<td><span>{{$triaje->fr}}</span></td>
				<td><span>{{$triaje->pa}}</span></td>
				<td><span>{{$triaje->t}}</span></td>
			</tr>
		</tbody>
	</table>
	
	<table style="margin-top:0px;">
		<tbody>
			<tr>
				<th style="border-top:1px solid black">Peso</th>
				<th style="border-top:1px solid black">Talla</th>
			</tr>
			<tr>
				<td><span>{{$triaje->peso}}</span></td>
				<td><span>{{$triaje->talla}}</span></td>
			
			</tr>
		</tbody>
	</table>

	<table style="margin-top:0px;">
		<tbody>
			<tr>
				<th style="text-align:left; padding-left:15px; border-top:1px solid black">MOTIVO DE LA CONSULTA:</th>
			</tr>
			<tr>
				<td style="text-align:left; padding-left:15px;"><span>{{$triaje->motivo}}</span></td>
			</tr>
			<tr>
				<th style="text-align:left; padding-left:15px;">SINTOMATOLOGÍA:</th>
			</tr>
			<tr>
				<td style="text-align:left; padding-left:15px;"><span>{{$triaje->sintomatologia}}</span></td>
			</tr>
			<tr>
				<th style="text-align:left; padding-left:15px;">ANTECEDENTES:</th>
			</tr>
			<tr>
				<td style="text-align:left; padding-left:15px;"><span>{{$triaje->antecedentes}}</span></td>
			</tr>
			<tr>
				<th style="text-align:left; padding-left:15px;">TIPOS DE PRUEBA APLICADA:</th>
			</tr>
			<tr>
				<td style="text-align:left; padding-left:15px;"><span>{{$triaje->pruebas}}</span></td>
			</tr>
		</tbody>
	</table>
	<table style="margin-top:0px;">
		<tbody>
		<tr>
				<td class="w-20"><strong>Personal responsable</strong></td>
				<td class="w-20"><span>{{$triaje->responsable}}</span></td>
			</tr>	
			<tr>
				<th style="text-align:left; border-top:0px solid black">PRIORIDAD: <span>{{romanos($triaje->prioridad)}}</span></th>
				<th style="text-align:left; border-top:0px solid black">ESPECIALISTA: <span>{{$especialista}}</span></th>
			</tr>
			<tr>
				<th style="text-align:left;" colspan=2>REFERENCIA: 
				@if($triaje->referencia==1)
				<span >Psicología</span>
				@elseif($triaje->referencia==2)
				<span >Psiquiatría</span>
				@elseif($triaje->referencia==3)
				<span >Psicología y Psiquiatría</span>
				@endif
			</th>
			</tr>
		</tbody>
	</table>

</body>

</html>