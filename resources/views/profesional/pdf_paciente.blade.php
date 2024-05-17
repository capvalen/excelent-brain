<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Pdf para el paciente</title>
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

    ul, ul li{
      list-style: decimal-leading-zero inside;
    }

    .d-block { display: block }
    p {font-size: 14px; font-family:'Montserrat-Light'; color: #495057}

    .position-absolute {position: absolute;}
    .position-relative {position: relative;}

    .top-0 {top: 0;}
    .left-0 {left: 0;}
    .right-0 {right: 0;}

    /* .lh-30 { line-height: 30px }
    .lh-25 { line-height: 25px }
    .lh-15 { line-height: 15px } */

    .title-h1 {font-size: 22px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}
    .title-h2 {font-size: 20px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}
    .title-h3 {font-size: 19px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}
    .title-h4 {font-size: 18px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}
    .title-h5 {font-size: 16px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}
    .title-h6 {font-size: 14px; font-family:'Montserrat-Bold'; color: #6b61a6; padding-bottom: 0px; margin-bottom: 0px;}

    .purple-color { color: #6b61a6; }
    .black-color { color: #495057; }

    .my-25 {padding-top: 20px; padding-bottom: 20px }

    .py-50 {padding-top: 40px; padding-bottom: 40px }
    .py-30 {padding-top: 10px; padding-bottom: 10px }
    .py-25 {padding-top: 20px; padding-bottom: 20px }
    .py-15 {padding-top: 10px; padding-bottom: 10px }
    .py-5  {padding-top: 5px ; padding-bottom: 5px}
    .py-0  {padding-top: 0px ; padding-bottom: 0px}

    .px-50 {padding-left: 50px; padding-right: 50px}
    .px-25 {padding-left: 25px; padding-right: 25px}
    .px-15 {padding-left: 15px; padding-right: 15px}
    .px-5  {padding-left: 5px; padding-right: 5px}
    
    .pr-50  {padding-right: 50px}
    .pr-25  {padding-right: 25px}
    .pr-15  {padding-right: 15px}
    .pr-5  {padding-right: 5px}

    .pt-50  {padding-top: 40px}
    .pt-25  {padding-top: 20px}
    .pt-15  {padding-top: 10px}
    .pt-5  {padding-top: 5px}
   
    .pb-50  {padding-bottom: 40px}
    .pb-25  {padding-bottom: 20px}
    .pb-15  {padding-bottom: 10px}
    .pb-5  {padding-bottom: 5px}



    .text-center  {text-align: center}
    .text-justify {text-align: justify}
    .text-start   {text-align: start}

    /* body */
    .pdf {
      width: 100%;
      min-height: 100vh;
      background: #F2B201;
      border-radius: 0 150px 0 150px;
      position: relative;
      top: 0;
      /* padding-bottom: 50px;  */
      bottom: 0;
    }

    .pdf-content {
      width: 100%;
      min-height: 100vh;
      background: #fff;
      border-radius: 0 180px 0 180px;
      position: relative;
    }

    .logotipo {
      width: 90%;
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      margin: auto;
      z-index: -10;
      opacity: .15;
    }

    /* header */
    .logotipo-header {
      width: 350px;
      margin: 25px 0;
    }

    .date {
      transform: translateY(5px)
    }

    
  </style>
</head>
<body>
  <div class="pdf">
    <div class="pdf-content">
      <img src="{{public_path('/img/logoem.png')}}" alt="Excelentemente" class="logotipo">


      {{-- contenido --}}
      <div class="header text-center">
        <img 
        src="{{public_path('/img/logoem.png')}}" 
        alt="Logotipo Excelentemente"
        class="logotipo-header"
        >
      </div>

      {{-- historias --}}
      <div class="px-50">
        {{-- Paciente --}}
        <div>
          <p class="title-h2 text-center">Paciente</p>
          <p>
            <span class="title-h5">Nombre: </span>
            <span>{{ strtoupper($historia->name) }} {{strtoupper($historia->nombres)}}</span>
          </p>

          <p>
            <span class="title-h5">Edad: </span>
            <span>{{ $age }}</span>
          </p>

          <p>
            <span class="title-h5">Número: </span>
            <span>{{ $historia->phone }}</span>
          </p>
          
          <p>
            <span class="title-h5">Fecha de nacimiento: </span>
            <span>{{ $historia->birth_date }}</span>
          </p>
         
          <p>
            <span class="title-h5">Ocupación: </span>
            <span>{{ $historia->occupation }}</span>
          </p>

          <p>
            <span class="title-h5">Estado civil: </span>
            <span>
              @if ($historia->marital_status == 1)
              Soltero
              @elseif ($historia->marital_status == 2)
              Casado
              @elseif ($historia->marital_status == 3)
              Viudo
              @elseif ($historia->marital_status == 4)
              Divorciado
              @elseif ($historia->marital_status == 5)
              Conviviente
              @endif
            </span>
          </p>
        </div>

        <br>

        {{-- Historia Psquiátrica --}}
        <div>
          @if ( $historia->initial_psychiatric_history )
            <div class="py-30">
              <div class="position-relative">
                <p class="title-h2 text-center">Historia Psquiátrica</p>
                @if ($historia->initial_psychiatric_history->created_at) 
                  <p class="title-h5 date position-absolute right-0 top-0">Fecha {{ date('d-m-Y',strtotime($historia->initial_psychiatric_history->created_at)) }}</p>
                @else
                  <p class="title-h5 date position-absolute right-0 top-0">Sin fecha</p>
                @endif
              </div>
            </div>

           {{-- Diagnostico --}}
            <div>
              <p class="title-h3 py-15">Diagnóstico</p>

              <div class="px-15">
                <div>
                  <p class="title-h4">1. Antecedentes generales y familiares</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->general_antecedent }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">2. Signos y síntomas principales</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->main_signs_symptoms }}
                  </p>
                </div>

                <div>
                  <p class="title-h4">3. Problema actual</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->illness }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">4. APC</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->apc }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">5. Lenguaje</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->languaje }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">6. Pensamiento</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->thought }}  
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">7. Afecto</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->affect }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
                
                <div>
                  <p class="title-h4">8. Percepción</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->percetion }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
               
                <div>
                  <p class="title-h4">9. Función superior</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->superior_function }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">10. Abstracción</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->abstraction }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">11. Conciencia</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->conscience }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">12. Conciencia</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->conscience }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">13. Insight</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->insight }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">14. Problemas Diagnóstico</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->diagnostic_problems }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">15. Diagnóstico</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->diagnostic }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">16. Plan</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychiatric_history->plan }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              </div> 
            </div>
          @endif
        </div>
        
        <br>

        {{-- Historia Psicológica --}}
        <div>
          @if ( $historia->initial_psychological_history )
            <div class="py-30">
              <div class="position-relative ">
                <p class="title-h2 text-center">Historia Psicológica</p>
                @if ($historia->initial_psychological_history->created_at)
                <p class="title-h5 date position-absolute right-0 top-0">Fecha {{ date('d-m-Y',strtotime($historia->initial_psychological_history->created_at)) }} </p>
                @else
                <p class="title-h5 date position-absolute right-0 top-0">Sin fecha</p>
                @endif
              </div>
            </div>

            {{-- Diagnostico --}}
            <div>
              <p class="title-h3 py-15">Diagnóstico</p>

              <div class="px-15">
                <div>
                  <p class="title-h4">1. Problema actual</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->illness }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">2. Antecedentes</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->antecedent }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">3. Dinámica</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->dynamic }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">4. Actitud actual</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->attitude }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              
                <div>
                  <p class="title-h4">5. Dx</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->dx }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>

                <div>
                  <p class="title-h4">6. Plan</p>
                  <p class="py-0 d-block lh-30">
                    {{ $historia->initial_psychological_history->plan }}
                  </p>
                  {{-- <p class="title-h6 >Fecha: <span class="">15/12/12</span></p> --}}
                </div>
              </div> 
            </div>
          @endif
        </div>

        <br>
        
        {{-- Evolcuiones --}}
        <div>
          @if ( $historia->medical_evolutions ) 
            <div>
              <p class="title-h3 py-0">Evoluciones</p>
              
              <ul class="px-15">
                @foreach ($historia->medical_evolutions as $evolution)
                  <br>
                  <li class="title-h4 px-15"> {{ $evolution->date }} </li>
                  <div class="px-25">
                    <p class="py-0"><span class="title-h6">Contenido:</span> <span class="text-justify">{{ $evolution->content }}</span></p>
                  </div>
                @endforeach
              </ul>
            </div>
          @endif
          @if( $historia->full=='no')
          <p><span class="title-h6">Nota:</span> Contenido restringido a 3 meses</p>
          @endif
        </div>
      </div>


    </div>
  </div>
</body>
</html>