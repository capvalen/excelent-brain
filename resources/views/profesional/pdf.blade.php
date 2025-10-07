<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir Receta</title>
</head>
<body>
    @php
    use Carbon\Carbon;
    
    // Aseguramos que el ID del profesional sea consistente a lo largo del documento
    $professionalId = $receta->professional_id;
    $professionalName = $professionalId ? $receta->professional->name : 'CENTRO PSICOLOGICO EXCELENTEMENTE';
    @endphp
    
    <div class="pdf">
        <div class="pdf-content">
            <div class="header__content" style="margin-top:10px">
                
                @if (in_array($professionalId, [32, 33, 34]))
                    <img src="{{ public_path('img/logo-reportee.png') }}" alt="Excelentemente" class="header-logo-big">
                @else
                    <img src="{{ public_path('img/logo-reportee.png') }}" alt="Excelentemente" class="header-logo">
                @endif

                <div class="header" style="margin-top:50px">
                    @if (!in_array($professionalId, [32, 33, 34]))
                        <p class="header__name">{{ $professionalName }}</p>
                        <p class="header__sub">MÉDICO PSIQUIATRA</p>
                    @endif
                
                    @switch($professionalId)
                        @case(null)
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 45805 - RNE N° 23848</p>
                            @endif
                            @break
                        @case(5)
                            <p class="header__sub">TERAPEUTA DE FAMILIAS Y PAREJAS</p>
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 45805 - RNE N° 23848</p>
                            @endif
                            @break
                        @case(15)
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 74376 - RNE N° 43202</p>
                            @endif
                            @break
                        @case(19)
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 068076 - RNE N° 047621</p>
                            @endif
                            @break
                        @case(24)
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 67656 - RNE N° 48050</p>
                            @endif
                            @break
                        @case(27)
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 85900 - RNE ET</p>
                            @endif
                            @break
                        @case(32)
                        @case(33)
                        @case(34)
                            {{-- No se muestra CMP ni RNE --}}
                            @break
                        @default
                            @if (!in_array($professionalId, [32, 33, 34]))
                                <p class="header__sub">CMP N° 45805 - RNE N° 23848</p>
                            @endif
                    @endswitch
                
                    <p class="header__sub">Consultas previa cita, llamar al celular: </p>
                    <p class="header__sub">Sede El Tambo: 996 644 350 | Sede San Carlos: 976 577 368 </p>
                </div>
                <br>
                <div class="separate"></div>
                <div class="separate"></div>
                <br>
            </div>

            <div class="pdf__body">
                <div class="body__paciente">
                    <div class="paciente__name">
                        <span class="paciente__title">Paciente:</span>
                        <small class="font-size-small text-capitalize">{{$receta->patient->name}} {{$receta->patient->nombres}} </small>
                        <span class="paciente__title">DNI:</span>
                        <small class="font-size-small">{{$receta->patient->dni}} </small>
                        <span class="paciente__title">Edad:</span>
                        <small class="font-size-small">@php
                            $fecha_nacimiento = Carbon::parse($receta->patient->birth_date ?? Carbon::now());
                            $edad = $fecha_nacimiento->diffInYears(Carbon::now());
                            echo "$edad años";
                        @endphp</small>

                        <span class="paciente__title">Diagnóstico:</span>
                        @foreach ($receta->patient->cies as $cie)
                            <small class="font-size-small">
                               {{ $cie->code }}
                            </small>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="body__receta">
                        <p class="body-receta">Receta</p>
                    </div>

                    <table class="body__table">
                        <thead class="table__head">
                            <tr>
                                <td class="border-table-right">Cant.</td>
                                <td class="border-table-right">N. Genérico</td>
                                <td class="border-table-right">N. Comercial</td>
                                <td class="border-table-right">Concent.</td>
                                <td class="border-table-right">Present.</td>
                                <td class="border-table-right">Vía</td>
                                <td>Indicaciones</td>
                            </tr>
                        </thead>

                        <tbody class="table__body">
                            @foreach($receta->kairos as $kairo)
                                <tr>
                                    <td class="columna celda-center border-table-right">
                                        {{ $kairo->pivot->amount }}
                                    </td>
                                    <td class="columna columna-1 border-table-right">
                                        {{$kairo->name}}
                                    </td>
                                    <td class="columna columna-1 border-table-right">
                                        {{$kairo->tradename}}
                                    </td>
                                    <td class="columna columna-1 border-table-right">
                                        {{$kairo->concentration}}
                                    </td>
                                    <td class="columna columna-1 border-table-right">
                                        {{$kairo->presentation}}
                                    </td>    
                                    <td class="columna columna-1 border-table-right">
                                        {{$kairo->pivot->way}}
                                    </td>
                                    <td class="columna">
                                        {{$kairo->pivot->indications}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pdf__footer">
                    <div class="footer__contact">
                        <table class="contact__recipe" cellspacing="10">
                            <tr>
                                <td class="contact__recipe-item1">
                                    <p>Fecha: {{date('d/m/Y',strtotime($receta->attention_date))}}</p>
                                </td>
                                <td class="contact__recipe-item2">
                                    @if ($receta->effective_date)
                                    <p>Fecha de su próxima visita: {{ date('d/m/Y',strtotime($receta->effective_date)) }}</p>
                                    @else
                                    <p>Fecha de su próxima visita: Sin fecha</p>
                                    @endif
                                </td>                               
                            </tr>
                        </table>

                        <table class="contact__datos">
                            <thead>
                                <tr>
                                    <th>Dirección</th>
                                    <th>Nuestras redes</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="datos__item">
                                        <span>Jr. Rebagliatti N° 166 El Tambo - Huancayo</span><br>
                                        <span>Av. Uruguay N° 595 San Carlos - Huancayo</span><br>
                                        <span><strong>Visítanos:</strong> https://excelentemente.org</span>
                                    </td>

                                    <td class="datos__item">
                                        <div class="icon-redes">
                                            <span><img src="{{public_path('img/5968764.png')}}" alt="Facebook"></span>
                                            <span><img src="{{public_path('img/174883.png')}}" alt="Youtube"></span>
                                            <span><img src="{{public_path('img/174855.png')}}" alt="Instagram"></span>
                                            <span><img src="{{public_path('img/3046120.png')}}" alt="Tiktok"></span>
                                            <span><img src="{{public_path('img/3669986.png')}}" alt="Spotify"></span>
                                        </div>
                                        <span class="icon-redes">@excelentementepsicologia</span><br>
                                        <span class="icon-redes"><strong>PodCast:</strong> Excelentemente Podcast</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="footer__firma">
                        @php
                            $showSignature = isset($receta) && $receta->signature == 1;
                        @endphp
                        
                        @if($showSignature && in_array($professionalId, [5, 24, 27, 32, 34]))
                            @switch($professionalId)
                                @case(24)
                                    <img src="{{ public_path('img/firma-dra-grecia.png') }}" alt="Firma" class="img-firma">
                                    @break
                                @case(5)
                                    <img src="{{ public_path('img/firmadoc3.png') }}" alt="Firma" class="img-firma">
                                    @break
                                @case(27)
                                    <img src="{{ public_path('img/firma-dra-nancy.jpg') }}" alt="Firma" class="img-firma">
                                    @break
																@case(32)
                                    <img src="{{ public_path('img/firma_wendy.jpg') }}" alt="Firma" class="img-firma">
                                    @break
                                @case(34)
                                    <img src="{{ public_path('img/firma_ursula.jpg') }}" alt="Firma" class="img-firma">
                                    @break
                            @endswitch
                        @endif
                    </div>
                </div>
            </div>
            <div class="botica__info">
                COTIZA TUS MEDICAMENTOS AL:<STRONG> 952 945 143 </STRONG> | <i>JUNTOS CUIDAMOS TU <STRONG>SALUD MENTAL</STRONG></i>
            </div>
        </div>
    </div>
</body>
</html>

<style>
    /* Mantener los mismos estilos que tenías antes */
    *{
        margin: 0px;
        padding: 0px;
    }

    body {
        font-family: 'Roboto', sans-serif;
        margin: auto;
        background: #2F4A99;
    }

    ul li{
        margin-left: 25px;
        list-style: disc;
    }

    .header__name {
        font-size: 30px;
        color: #0069b3;
        font-family:'Montserrat-Bold';
        text-transform: uppercase;
    }

    .header__sub{
        color:#484554;
        font-size: 15px;
        font-family:'Montserrat-Bold';
    }
    .logotipo {
        width: 90%;
        position: absolute;
        top: 15%;
        left: 6%;
        z-index: -10;
        opacity: .05
    }
    
    .light-signature{
        font-size:15px;
        font-family:'Montserrat-Light';
    }

    #container{
        margin: 150px auto;
        width: 600px;
    }

    .header__content {
        width: 80%;
        position: relative;
        margin: 0 auto 0; 
    }
    .header-logo {
        width: 250px;
        top: 00px;
        left: -40px;
        position: absolute
    }

    .header-logo-big {
    width: 400px !important;
    position: relative !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    margin-bottom: -50px !important;
    display: block !important;
        
    }
    .header {
        text-align: center;
        z-index: 100;
    }

    .separate {
        width: 100%;
        height: 5px;
        margin: 0 auto 5px;
        background: #0069b3;
    }

    .pdf__body {
        width: 80%;
        position: relative;
        z-index: 100;
        margin: 0 auto 0;
    }

    .pdf {
        width: 100%;
        background: #F2B201;
        border-radius: 0 150px 0 150px;
        position: absolute;
        top: 0;
        bottom: 0;
    }

    .pdf-content {
        width: 100%;
        background: #fff;
        border-radius: 0 180px 0 180px;
        position: absolute;
        top: 0;
        bottom: 0;
    }

    /* Cuerpo del pdf */

    .paciente__title {
        color: #484554;
        text-align: center;
        font-family:'Montserrat-Bold';
        font-size: 15px;
        margin-left: 15px;
    }

    .paciente__name {
        background: #fafafa;
        width: 100%;
        font-family: 'Montserrat-Light';
        font-size: 25px;
        padding: 0 0 10px 0;
        border: 1px solid;
        border-radius: 5px;
    }

    .body-receta {
        color: #484554;
        text-align: center;
        font-family:'Montserrat-Bold';
        font-size: 25px
    }

    .body__receta {
        margin: 10px 0;
    }

    /* Tabla */
    table{
        width: 100%;
        border-collapse: separate
    }
    
    .body__table {
        text-align: left;
        background: #fafafa;
        border: 1px solid;
    }

    .border-table-right {
        border-right: 2px solid
    }

    .table__head {
        border-bottom: 1px solid
    }

    .table__head td {
        padding: 5px;
        height: 25px !important;
        font-family: 'Montserrat-Bold' !important;
        text-align: center;
        color: #484554;
        font-size:13px;
    }

    .table__body {
        /* padding: 5px; */
    }
    .table__body td {
        padding: 2px 12px;
        height: 20px !important;
        /* border-bottom: 1px solid; */
        text-align: center;
        color: #495057;
        font-size: 12px;
    }

    .body__message {
        float: right;
        color: #495057;
    }

    .columna-1 {
        text-align: left !important;
        padding-left: 25px;
        /* margin-left: 15px !important; */
    }

    .celda-center {
        text-align: center;
    }

    .pdf__footer {
        width: 100%;
        position: relative;
        transform: translateX(-10px);
        /* top: 59%; */
        /* left: 0; */
        margin: 25px auto 0;
    }

    .footer__contact {
        float: left;
        width: 70%;
        /* padding-right: 15px; */
    }


    .datos__item img{
        width: 25px;
            
    }

    .datos__item{
        font-size: 13px;
        text-align: left;
    }

    .contact__recipe {
        width: 85%;
    }

    .contact__recipe-item1 {
        width: 38%;
        padding: 7px 15px 7px 15px;
        margin-left: 15px;
        color: #495057;
        background: #fafafa;

        border-radius: 5px;
        border: 1px solid;
                    font-size: 12px;
    }
    .botica__info{
        width: 80%;
        padding: 7px 15px 7px 15px;
        margin-top: 180px;
        margin-left: 90px;
        color: #495057;
        background: #fafafa;
        border-radius: 5px;
        border: 2px solid;
        text-align: center;
        font-size: 12px;
    }
    
    /* Datos de la siguiente consulta */
    .contact__recipe-item2 {
        width: 62%;
        padding: 7px 15px 7px 15px;
        background: #fafafa;

        /* margin-left: 15px; */
        color: #495057;
        border-radius: 5px;
        border: 1px solid;
                    font-size: 10px;
    }


    /* datos de contactos */
    .contact__datos {
        width: 100%;
        clear: both;
        margin-left: 10px;
        padding-right: 25px;
    }

    .contact__datos td{
        /* padding-right: 15px; */
    }

    .contact__datos thead tr th {
        text-align: left !important;
    }

    .icon-redes {
        margin-top: 8px;
        text-align: left;
    }

    /* firma  */
    .footer__firma {
        width: 25%;
        top: 0%;
        right: 0;
        text-align: center;
        position: absolute;
    }
        .footer__firma img {
            display: block;
            width: 350px;
            top:-0px;
            position: absolute;
            right: -75px;
        }

    .font-size-small {
        font-size: 14px
    }

    .bg-gray {
        background: rgba(236, 236, 236, 0.521);
    }
            .text-capitalize{text-transform: capitalize;}

</style>