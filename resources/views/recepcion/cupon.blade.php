<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel = "preconnect" href = "https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
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

        #container{
            margin: 150px auto;
            width: 600px;
        }

        table{
            width: 100%;
            background-color:white;
            border-collapse: collapse;
            text-align: left;
            border: 0;
        }

        .header{
            padding: 0px 10px 0px 10px;
        }


        .text-header{
            margin-top: 20px;
            text-align: center;
        }


        .titulo{
            margin-top: 8px;
            font-size: 10px;
        }

        .titulo-oficial{
            font-size: 10px;
        }

        .phones{
            font-size: 8px;
            font-style: italic;
        }

        .branding{
            font-size: 8px;
            border: 1px rgb(50, 163, 15) solid;
            font-style: italic;
            text-align: center;
        }

        .row{
            margin-top: 60px;
        }

        .mb-4{
            padding: 0px 10px 0px 10px;
        }

        .columna{
            text-align: center;
            text-transform: capitalize;
        }
        .content-1{
            position: relative;
        }
        .parte-1-izquierda{
            float: left;
            position: absolute;
            top: 1%;
        }
        .parte-1-derecha{
            float: right;
            position: absolute;
            top: 1%;
        }
        .content-2{
            position: relative;
        }
        .parte-2-izquierda{
            clear: both;
            position: absolute;
            top: 4%;

        }
        .parte-2-derecha{
            float: right;
            position: absolute;
            top: 4%;

        }
        .content-3{
            clear: both;
        }
        .card{
            font-size:9px;
        }
        
        .slogan{
            margin-top: 5px;
            font-style: italic;
            font-size: 8px;
            text-align: center;
        }
        .note_slogan{
            margin-top: 5px;
            font-size: 8px;
            text-align: center;
        }
        h3{
            font-size: 8px;
        }
        #fecha{
            margin-top: 5px;
        }
        .body{
            text-align: center;
            padding-top: 10px;
            font-size: 10px;
        }
        .body h2{
            font-size: 10px;
        }
        .main-data{
            text-align: left;
            padding: 0px 15px 0px 15px;
        }
        .name{
            text-transform: uppercase;
        }
        .address{
            font-size: 8px;
            margin-top: 10px;
        }
    </style>
    <title>Imprimir Cupón</title>
</head>
<body>
	@php
	use Carbon\Carbon;
	@endphp
    <div class="container">
        <div class="container-fluid p-0">
            <div class="header">
                <div class="text-header">
                    <h2 class="titulo">Centro Psicológico y Psiquiátrico Excelentemente </h2>
                    <h2 class="titulo-oficial">EXCELENTEMENTE E.I.R.L.</h2>
                    <h3>RUC: 20601862329</h3>
                    <p class="address">Jr.Rebagliatti Nro 166 - El Tambo - Huancayo</p>
                    <p class="phones">064 603228 - 984894659 - 996644350 - www.excelentemente.org</p>
                </div>
            </div>
            <div class="body">
                    <h2>NOTA DE VENTA</h2>
                    <h2>E001-00{{$appointment->id}}</h2>
                    <div class="main-data">
											<p>EMISIÓN:  
												<span>@php
												$fecha = Carbon::parse( $appointment->date ); echo $fecha->format('d/m/Y');
												@endphp</span>
											</p>
											<p>DNI: {{$patient[0]->dni}}</p>
											<p class="name">CLIENTE: {{$patient[0]->name}}</p>
											<p>DIRECCIÓN: {{$patient[0]->address->address}}</p>
											<p>USUARIO: {{$usuario[0]->email}}</p>
                    </div>
                    <table style="padding: 0px 15px 0px 15px; margin-top:10px;">
                        <thead>
                            <tr style="font-weight: bold;">
                                <td>SERVICIO</td>
                                <td style="text-align: right;">SUBTOTAL</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if($appointment->type == 1)
                                Terapia Inicial niño/adolescente
                                @elseif ($appointment->type == 2)
                                Terapia Inicial adulto
                                @elseif ($appointment->type == 3)
                                Terapia Inicial pareja
                                @elseif ($appointment->type == 4)
                                Terapia Inicial familiar
                                @elseif ($appointment->type == 5)
                                Terapia continua niño/adolescente
                                @elseif ($appointment->type == 6)
                                Terapia continua adulto
                                @elseif ($appointment->type == 7)
                                Terapia continua pareja
                                @elseif ($appointment->type == 8)
                                Terapia continua familiar
                                @elseif ($appointment->type == 9)
                                Orientación Vocacional
                                @elseif ($appointment->type == 10)
                                Sucamec inicial
                                @elseif ($appointment->type == 11)
                                Sucamec renovación
                                @elseif ($appointment->type == 12)
                                Kúrame
                                @endif
                                    -
                                @if($appointment->clasification == 1)
                                Psiquiátrica
                                @else
                                Psicológica
                                @endif
                                </td>
                                <td style="text-align: right;">
                                    {{$appointment->payment->price}}
                                </td>
														</tr>
														@if($appointment->payment->observation <>'')
														<tr><td><strong>Obs.:</strong> {{$appointment->payment->observation}}</td></tr>
														@endif
                        </tbody>
                    </table>
                    <hr style="width: 100%; margin-top:5px;">
                    <table style="padding: 0px 15px 0px 15px; margin-top:10px;">
                        <thead>
                            <tr>
                                <td>DESCUENTO</td>
                                <td style="text-align: right;">S/ .00</td>
                            </tr>
                            <tr>
                                <td>OP.GRAVADA</td>
                                <td style="text-align: right;">S/ {{$appointment->payment->price - $appointment->payment->price*.18}}</td>
                            </tr>
                            <tr>
                                <td>I.G.V</td>
                                <td style="text-align: right;">S/ {{$appointment->payment->price*.18}}</td>
                            </tr>
                            <tr>
                                <td><strong>IMPORTE TOTAL</strong></td>
                                <td style="text-align: right;">S/ {{$appointment->payment->price}}</td>
                            </tr>
                        </thead>
                    </table>
            </div>
            <p class="slogan" style="margin-top:10px;">Tu salud mental es lo más importante</p>
						<p class="note_slogan">NOTAS: Este doc. es intercambiable por una Boleta o Factura</p>
            <p class="note_slogan">No hay devoluciones. Todo adelanto expira en 3 meses</p>

        </div>
    </div>

</body>
</html>
