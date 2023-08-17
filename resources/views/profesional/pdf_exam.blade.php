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
            color: #6b61a6;
            font-family:'Montserrat-Bold';
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

        .header {
            text-align: center;
            z-index: 100;
        }

        .separate {
            width: 100%;
            height: 5px;
            margin: 0 auto 5px;
            background: #2F4A99;
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
            background: #E6E6E6;
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
            background: #E6E6E6;
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
        }

        .table__body {
            /* padding: 5px; */
        }
        .table__body td {
            padding: 3px 15px;
            height: 25px !important;
            border-bottom: 1px solid;
            text-align: center;
            color: #495057;
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

        .contact__recipe {
            width: 35%;
        }

        .contact__recipe-item1 {
            width: 38%;
            padding: 7px 15px 7px 15px;
            margin-left: 15px;
            color: #495057;
            background: #E6E6E6;

            border-radius: 5px;
            border: 2px solid
        }
        
        /* Datos de la siguiente consulta */
        .contact__recipe-item2 {
            width: 62%;
            padding: 7px 15px 7px 15px;
            background: #E6E6E6;

            /* margin-left: 15px; */
            color: #495057;
            border-radius: 5px;
            border: 2px solid
        }


        /* datos de contactos */
        .contact__datos {
            width: 100%;
            clear: both;
            margin-left: 8px;
            padding-right: 25px;
        }

        .contact__datos td{
            /* padding-right: 15px; */
        }

        .contact__datos thead tr th {
            text-align: start !important;
        }

        .icon-redes {
            margin-top: 8px;
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
    </style>
    <title>Imprimir Examen</title>
</head>
    @foreach ($examsAll->value as $exam)
    <body>
        <div class="pdf">
            <div class="pdf-content">
                <div class="header__content">
                    <img src="https://excelentemente.org/consultorio-online/libs/logo.png" alt="Excelentemente" class="header-logo">
    
                    <div class="header" style="margin-top: 3rem;">
                        <p class="header__name">WILBERT BARZOLA HUAMAN</p>
                        <p class="header__sub">MÉDICO PSIQUIÁTRA</p>
                        <p class="header__sub">Consultas previa cita, llamar al celular: 996644350</p>
                        <p class="header__sub">TERAPEUTA DE FAMILIAS Y PAREJAS</p>
                        <p class="header__sub">CMP N° 45805 - RNE N° 23848</p>
                    </div>
        
                    <br>
                    <div class="separate"></div>
                    <div class="separate"></div>
                    <br>
                </div>

                <img src="https://excelentemente.org/consultorio-online/libs/logo.png" alt="Excelentemente" class="logotipo">

                <div class="pdf__body">
                    <div class="body__paciente">
                        <div class="paciente__name">
                            <span class="paciente__title">Paciente:</span>
                            <small class="font-size-small">{{ $data->patient->name }} </small>
                            
                            <span class="paciente__title">Diagnóstico:</span>
                            @foreach ($data->patient->cies as $cie)
                                <small class="font-size-small">
                                    {{ $cie->code }}
                                </small>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="body__receta">
                            <p class="body-receta">ORDEN DE EXAMEN</p>
                        </div>

                        <table class="body__table">
                            <thead class="table__head">
                                <tr>
                                    <td>#</td>
                                    <td>Tipo de examen</td>
                                    <td>Examen</td>
                                </tr>
                            </thead>

                            <tbody class="table__body">
                                @foreach ($exam as $arr)
                                <tr>
                                    <td class="columna">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="columna">
                                        @if ($arr->type === 1)
                                            Laboratorio
                                        @elseif ($arr->type === 2)
                                            Imagenología
                                        @elseif ($arr->type === 3)
                                            Otro
                                        @endif
                                    </td>
                                    <td class="columna">
                                        {{ $arr->name }}
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
                                        <p>Fecha: {{ $objectExam->attention_date }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table class="contact__datos">
                                <thead>
                                    <tr>
                                        <th>Central</th>
                                        <th>Dirección</th>
                                        <th>Nuestras redes</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                    <tr>
                                        <td class="datos__item">
                                            <p>- 996644350</p>
                                            <p>- (064) 603228</p>
                                        </td>
    
                                        <td class="datos__item">
                                            <span>Jr. Rebagliatti N° 166 El Tambo – Huancayo, Perú</span>
                                        </td>
    
                                        <td class="datos__item">
                                            <div class="icon-redes">
                                                <span><img src="https://icones.pro/wp-content/uploads/2021/02/facebook-icones.png" alt="Facebook"></span>
                                                <span><img src="https://cdn-icons-png.flaticon.com/512/174/174883.png" alt="Youtube"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="footer__firma">
                            <img src="https://excelentemente.org/consultorio-online/libs/firmadoc3.jpeg" alt="Facebook" class="img-firma">
                            {{-- <img src="https://icones.pro/wp-content/uploads/2021/02/facebook-icones.png" alt="Facebook" class="img-firma"> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    @endforeach

</html>
