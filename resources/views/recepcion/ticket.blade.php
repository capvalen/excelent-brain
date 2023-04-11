<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel = "preconnect" href = "https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <style>
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
        .titulo{
            text-align: center;
            font-size:13px;
            margin-top: -10px;
        }
        .slogan{
            font-size:8px;
            text-align: center;
        }
        h3{
            font-size: 10px;
            margin-top: -10px;
            text-align: center;
        }
        #fecha{
            margin-top: -10px;
        }
    </style>
    <title>Imprimir Ticket</title>
</head>
<body>
    <div class="container">
        <div class="container-fluid p-0">

            <h1 class="titulo">Clínica Psicológica y Psiquiátrica Excelentemente </h1>
            <h3>RUC: 20601862329</h3>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body m-sm-3 m-md-5">
                            <div class="mb-4">

                                Hola <strong> {{$patient[0]->name}}</strong>,
                                <br/>
                                Usted tiene una cita con el profesional:
                                <strong>{{$professional[0]->name}}</strong>
                                <br/>
                                <br/>
                                <p id="fecha">Fecha de la cita: {{date_format(date_create($appointment->date),'d/m/Y')}}</p>
                                <p>Hora de la cita: {{date_format(date_create($appointment->date.$schedule[0]->check_time),'h:i a')}} - {{date_format(date_create($appointment->date.$schedule[0]->departure_date),'h:i a')}}</p>
                                
                            </div>


                            <hr class="my-1" />


                            <p class="slogan">Tu bienestar personal es lo más importante</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
