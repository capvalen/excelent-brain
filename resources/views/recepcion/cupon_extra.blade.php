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
	$monedas =['Efectivo', 'Depósito bancario',  'POS', 'Aplicativo Yape', 'Banco: BCP', 'Banco: BBVA', 'Banco: Interbank', 'Banco: Nación', 'Banco: Scotiabank', 'Aplicativo Plin'];
@endphp
    <div class="container">
        <div class="container-fluid p-0">
            <div class="header">
                <div class="text-header">
                    <h2 class="titulo">Centro Psicológico y Psiquiátrico Excelentemente </h2>
                    <h2 class="titulo-oficial">EXCELENTEMENTE E.I.R.L.</h2>
                    <h3>RUC: 20601862329</h3>
                    <p class="address">Jr.Rebagliatti Nro 166 - El Tambo - Huancayo</p>
                    <p class="phones">064 603228 - 984894659 - 996644350 - https://excelentemente.org</p>
                </div>
            </div>
            <div class="body">
							
							@if( $extra_payment->type==6)
								<h2>EGRESO</h2>
							@else
								<h2>TICKET INTERNO</h2>
								<h2>E002-00{{$extra_payment->id}}</h2>
							@endif
							
                    <div class="main-data">
											<p>FECHA:  
												<span>@php
												$fecha = Carbon::parse( $extra_payment->date ); echo $fecha->format('d/m/Y');
												@endphp</span>
											</p>
											<p class="name">CLIENTE: {{$extra_payment->customer}}</p>
											<p>USUARIO: -</p>
											{{-- <p>DNI: {{$patient[0]->dni}}</p> --}}
											{{-- <p>DIRECCIÓN: {{$patient[0]->address->address}}</p> --}}
											 <p>MEDIO DE PAGO: {{ $monedas[$extra_payment->moneda-1] }}</p>
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
                                    @if($extra_payment->type == 0)
                                    Certificado
                                    @elseif($extra_payment->type == 1)
                                    Paquete de Membresía
                                    @elseif($extra_payment->type == 2)
                                    Pauete de Kurame
                                    @elseif($extra_payment->type == 3)
                                    Informe
                                    @elseif($extra_payment->type == 4)
                                    Otros
                                    @elseif($extra_payment->type == 5)
                                    Pago de cita
                                    @elseif($extra_payment->type == 6)
                                    Salida de dinero
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    {{$extra_payment->price}}
                                </td>
                            </tr>
														@if($extra_payment->observation <>'')
														<tr><td><strong>Obs.:</strong> {{$extra_payment->observation}}</td></tr>
														@endif
														@if($extra_payment->voucher <>'')
														<tr><td><strong>Comprobante:</strong> {{$extra_payment->voucher}}</td></tr>
														@endif
														@if($extra_payment->voucher_issued <>'' && $extra_payment->voucher_issued <> null )
														<tr><td><strong>Serie-Correlativo:</strong> {{strtoupper($extra_payment->voucher_issued)}}</td></tr>
														@endif
                        </tbody>
                    </table>
                    <hr style="width: 90%; margin:3px 5px; padding-left: 5px; ">
                    <table style="padding: 0px 15px 0px 15px; margin-top:10px;">
                        <thead>
                            <tr>
                                <td>DESCUENTO</td>
                                <td style="text-align: right;">
																<span>
																@if($extra_payment->rebaja >0)
																	{{ 'S/'.number_format($extra_payment->rebaja,2)}}
																@elseif($extra_payment->descuento >0)
																{{ $extra_payment->rebaja . '%'}}
																@endif
																</span>
																</td>
                            </tr>
                            <tr>
                                <td>OP.GRAVADA</td>
                                <td style="text-align: right;">S/ {{$extra_payment->price - $extra_payment->price*.18}}</td>
                            </tr>
                            <tr>
                                <td>I.G.V</td>
                                <td style="text-align: right;">S/ {{$extra_payment->price*.18}}</td>
                            </tr>
                            <tr>
                                <td><strong>IMPORTE TOTAL</strong></td>
                                <td style="text-align: right;">S/ {{$extra_payment->price}}</td>
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
