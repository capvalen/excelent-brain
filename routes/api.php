<?php

use App\Http\Controllers\AcuerdoController;
use App\Http\Controllers\AdittionalController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BurnController;
use App\Http\Controllers\CieController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExtrapaymentController;
use App\Http\Controllers\GadController;
use App\Http\Controllers\KairoController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Initial_psychiatric_historyController;
use App\Http\Controllers\Initial_psychological_historyController;
use App\Http\Controllers\Medical_evolutionController;
use App\Http\Controllers\Medical_examController;
use App\Http\Controllers\MillonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhqController;
use App\Http\Controllers\ScrController;
use App\Http\Controllers\Zung_anxietyController;
use App\Http\Controllers\Zung_depressionController;
use App\Http\Controllers\ExtrasController;
use App\Http\Controllers\LimboController;
use App\Http\Controllers\SimpleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ─── PUBLIC ───────────────────────────────────────────────────────────────────
Route::post('login', [AuthController::class, 'login']);
Route::get('listarPrecios', [ExtrasController::class, 'listarPrecios']);
Route::get('preciosMembresias', [ExtrasController::class, 'preciosMembresias']);

// ─── AUTHENTICATED ────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // Current authenticated user (password and remember_token are hidden via $hidden on the model)
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('professional');
        return response()->json([
            'user' => $user,
        ]);
    });

    // ── PATIENTS ──────────────────────────────────────────────────────────────
    Route::group([], function () {
        Route::resource('patient', PatientController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
        Route::post('patient/new', [PatientController::class, 'insertPatient']);
        Route::get('patientMine/{idProfesional}', [PatientController::class, 'patientMine']);
        Route::get('patientMineText/{texto}', [PatientController::class, 'patientMineText']);
        Route::get('patientById/{idPatient}', [PatientController::class, 'patientById']);
        Route::get('getPatient', [PatientController::class, 'getPatient']);
        Route::get('getLast10Patients', [PatientController::class, 'getLast10Patients']);
        Route::get('buscar/{dni}', [PatientController::class, 'buscarPaciente']);
        Route::get('buscarPacienteDB/{dni}', [PatientController::class, 'buscarPaciente']);
        Route::get('buscarPacienteSoloDNI/{dni}', [PatientController::class, 'buscarPacienteSoloDNI']);
        Route::get('searchPatientByNameDni/{nombre}', [PatientController::class, 'searchPatientByNameDni']);
        Route::get('getNamePatient/{id}', [PatientController::class, 'getNamePatient']);
        Route::get('getNameExam/{id}', [PatientController::class, 'getNameExam']);
        Route::get('getNames', [PatientController::class, 'getNames']);
        Route::get('patientEvolution/{idPaciente}', [PatientController::class, 'showEvolution']);
        Route::get('patientEvolution/{idPaciente}/{idUsuario}', [PatientController::class, 'showEvolution']);
        Route::get('datosPacienteSemaforo/{id}', [PatientController::class, 'datosPacienteSemaforo']);
        Route::get('listarCumpleanos/{mes}', [PatientController::class, 'listarCumpleanos']);
        Route::get('verReprogramaciones/{id}', [PatientController::class, 'verReprogramaciones']);
        Route::get('verHistorialFaltas/{id}', [PatientController::class, 'verHistorialFaltas']);
        Route::get('pedirSeguimientos', [PatientController::class, 'pedirSeguimientos']);
        Route::get('pedirSeguimientosActivos', [PatientController::class, 'pedirSeguimientosActivos']);
        Route::post('editarPariente/{id}', [PatientController::class, 'editarPariente']);
        Route::post('addHobbie/{idPatient}', [PatientController::class, 'addHobbie']);
        Route::post('insertarTriaje/{id}', [PatientController::class, 'insertarTriaje']);
        Route::post('insertarSemaforo', [PatientController::class, 'insertarSemaforo']);
        Route::post('eliminarSemaforo/{id}', [PatientController::class, 'eliminarSemaforo']);
        Route::post('crearSOS', [PatientController::class, 'crearSOS']);
        Route::post('quitarSOS', [PatientController::class, 'quitarSOS']);
        Route::get('pedirSOS', [PatientController::class, 'pedirSOS']);
        Route::post('crearComentarioSOS', [PatientController::class, 'crearComentarioSOS']);
        Route::get('listarComentariosSOS/{idSos}', [PatientController::class, 'listarComentariosSOS']);
        Route::post('agendarNuevaCita', [PatientController::class, 'agendarNuevaCita']);
        Route::get('updateFaults/{id}/{faults}', [PatientController::class, 'updateFaults']);
        Route::post('/subirArchivo', [PatientController::class, 'subirArchivo']);
        Route::post('/pedirArchivos', [PatientController::class, 'pedirArchivos']);
        Route::post('/pedirArchivosTriaje', [PatientController::class, 'pedirArchivosTriaje']);
        Route::get('xlsx_recep/{date}', [PatientController::class, 'createXlsx']);

        // Admin-only patient routes
        Route::middleware('role:administrador,recepcionista')->group(function () {
            Route::get('getLast10PatientsAdmin', [PatientController::class, 'getLast10PatientsAdmin']);
            Route::get('searchPatientByNameDniAdmin/{nombre}', [PatientController::class, 'searchPatientByNameDniAdmin']);
            Route::get('returnTotalPatients', [PatientController::class, 'returnTotalPatients']);
            Route::get('xlsx_admin/{date}', [PatientController::class, 'monthXlsx']);
            //Route::get('discharge/{id}/{idProfesional}', [PatientController::class, 'discharge']);
            Route::get('agePerMonth/{month}', [PatientController::class, 'agePerMonth']);
        });

        // Admin-only patient routes
        Route::middleware('role:administrador,recepcionista,profesional')->group(function () {
            Route::get('discharge/{id}/{idProfesional}', [PatientController::class, 'discharge']);
        });
    });

    // ── APPOINTMENTS ──────────────────────────────────────────────────────────
    Route::group([], function () {
        Route::resource('appointment', AppointmentController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
        Route::post('registrarHora', [AppointmentController::class, 'registrarHora']);
        Route::post('eliminarCita/{id}', [AppointmentController::class, 'eliminarCita']);
        Route::put('pagarCita/{id}', [AppointmentController::class, 'pagarCita']);
        Route::get('appointmentKurame/{id}', [AppointmentController::class, 'showKurame']);
        Route::get('appoitmentShow/{idUser}/{idDate}', [AppointmentController::class, 'showConsult']);
        Route::get('searchAppointment/{nombre}/{profesional?}/{fecha?}/{dni}', [AppointmentController::class, 'searchAppointment']);
        Route::get('searchByDateAppointment/{date}', [AppointmentController::class, 'searchByDateAppointment']);
        Route::post('updateStatus/{id}/{value}', [AppointmentController::class, 'updateStatus']);
        Route::get('updateModeAppoinment/{id}', [AppointmentController::class, 'updateMode']);
        Route::put('reprogramado/{id}', [AppointmentController::class, 'reprogramado']);
        Route::put('mandarVacio/{id}', [AppointmentController::class, 'mandarVacio']);
        Route::post('intercambiar', [AppointmentController::class, 'intercambiar']);
        Route::get('appoitmentEvolutions/{id}', [AppointmentController::class, 'getEvolutions']);
        Route::delete('deleteEvolution/{id}', [AppointmentController::class, 'deleteEvolution']);
        Route::get('profesional', [AppointmentController::class, 'getprof']);
        Route::get('departamentos', [AppointmentController::class, 'getDepartamentos']);
        Route::get('chart/{date}', [AppointmentController::class, 'getDataForCharts']);
        Route::get('ticket/{id}', [AppointmentController::class, 'createTicket']);
        Route::get('pdfCupon/{id}', [AppointmentController::class, 'cuponMismaSerie']);
        Route::get('pdfExtraCupon/{id}', [AppointmentController::class, 'pdfExtraCupon']);
        Route::get('cuponMembresia/{id}/{monto}', [AppointmentController::class, 'cuponMembresia']);
        Route::get('triajePDF/{id}', [AppointmentController::class, 'pdfTriaje']);

        // Admin-only appointment routes
        Route::middleware('role:administrador,recepcionista')->group(function () {
            Route::get('reportsAppointments/{date}', [AppointmentController::class, 'reportsAppointments']);
            Route::get('getPatientsMonth/{date}', [AppointmentController::class, 'getPatientsMonth']);
            Route::get('getProfessionalSummaryAppointment/{id}/{date}', [AppointmentController::class, 'getProfessionalSummaryAppointment']);
            Route::get('getAllPatients', [AppointmentController::class, 'getAllPatients']);
            Route::get('getPatientsPerMonth/{date}/{id}', [AppointmentController::class, 'getPatientsPerMonth']);
            Route::get('getPatientsPerDay/{date}/{id}', [AppointmentController::class, 'getPatientsPerDay']);
        });
    });

    // ── PAYMENTS ──────────────────────────────────────────────────────────────
    Route::group([], function () {
        Route::resource('appointment/payment', PaymentController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
        Route::resource('paymentExtra', ExtrapaymentController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
        Route::post('egresoExtra', [ExtrapaymentController::class, 'egresoExtra']);
        Route::post('borrarPagoExtra', [ExtrapaymentController::class, 'borrarPagoExtra']);
        Route::put('/dividirPago/{id}', [ExtrasController::class, 'dividirPago']);
        Route::get('verAdjuntoPago/{id}', [ExtrasController::class, 'verAdjuntoPago']);
        Route::post('subirArchivoPago', [ExtrasController::class, 'subirArchivoPago']);
        Route::post('subirArchivoAcuerdo', [ExtrasController::class, 'subirArchivoAcuerdo']);
        Route::post('subirArchivoTriaje', [ExtrasController::class, 'subirArchivoTriaje']);
        Route::post('eliminarAdjunto/{id}', [ExtrasController::class, 'eliminarAdjunto']);

        // Admin-only payment routes
        Route::middleware('role:administrador,recepcionista')->group(function () {
            Route::post('getAllExtraPayments', [PaymentController::class, 'getAllExtraPayments']);
            Route::post('getExtraPaymentsByDay', [PaymentController::class, 'getExtraPaymentsByDay']);
            Route::post('editarPagoExtra', [PaymentController::class, 'editarPagoExtra']);
            Route::get('/ticketCierreCaja/{fecha}/{usuario}/{idSede}', [ExtrapaymentController::class, 'ticketCierreCaja']);
            Route::get('listarDeudas/{fecha}', [ExtrasController::class, 'listarDeudas']);
            Route::post('pagarDeudaMembresia', [ExtrasController::class, 'pagarDeudaMembresia']);
            Route::post('insertarDeudasSeguimiento', [ExtrasController::class, 'insertarDeudasSeguimiento']);
            Route::get('pedirHistorialDeudas/{idPaciente}', [ExtrasController::class, 'pedirHistorialDeudas']);
        });
    });

    // ── USERS (admin only) ────────────────────────────────────────────────────
    Route::middleware('role:administrador,recepcionista')->group(function () {
        Route::delete('user/{id}', [AuthController::class, 'deleteUser']);
        Route::put('user/{id}', [AuthController::class, 'updateUser']);
        Route::get('cargarUsuarios', [ExtrasController::class, 'cargarUsuarios']);
        Route::post('nuevoUsuarioBasico', [ExtrasController::class, 'nuevoUsuarioBasico']);
        Route::post('actualizarDatosUsuarioBasico', [ExtrasController::class, 'actualizarDatosUsuarioBasico']);
        Route::post('eliminarUsuario', [ExtrasController::class, 'eliminarUsuario']);
    });

    // ── REPORTS (admin only) ──────────────────────────────────────────────────
    Route::middleware('role:administrador,recepcionista')->group(function () {
        Route::get('dataTableProf', [ProfessionalController::class, 'dataTableProf']);
        Route::get('getPatientsWhitEvolution/{date}', [Medical_evolutionController::class, 'getPatientsWhitEvolution']);
        Route::get('getPatientsPsiq/{date}', [Medical_evolutionController::class, 'getPatientsPsiq']);
        Route::post('pedirReporte/{idReporte}', [ExtrasController::class, 'pedirReporte']);
        Route::post('pedirReporteGerencial/{idReporte}', [ExtrasController::class, 'pedirReporteGerencial']);
        //Route::post('buscarCartera', [ExtrasController::class, 'buscarCartera']);
        Route::post('actualizarPrecioAdmin', [ExtrasController::class, 'actualizarPrecioAdmin']);
        Route::post('crearPrecioNuevo', [ExtrasController::class, 'crearPrecioNuevo']);
        Route::get('listarPreciosTodos', [ExtrasController::class, 'listarPreciosTodos']);
        Route::post('reportsJimmy', [SimpleController::class, 'reportsJimmy']);
    });

    // ── REPORTS (incluyendo profesional) ──────────────────────────────────────────────────
    Route::middleware('role:administrador,recepcionista,profesional')->group(function () {
        Route::post('buscarCartera', [ExtrasController::class, 'buscarCartera']);
    }); 

    // ── EXTRAS ────────────────────────────────────────────────────────────────
    Route::group([], function () {
        Route::post('nuevoAviso', [ExtrasController::class, 'nuevoAviso']);
        Route::post('actualizarAviso', [ExtrasController::class, 'actualizarAviso']);
        Route::get('listarAvisos/{fecha}', [ExtrasController::class, 'listarAvisos']);
        Route::get('listarAvisosAhora/{fecha}', [ExtrasController::class, 'listarAvisosAhora']);
        Route::post('nuevoInteresado', [ExtrasController::class, 'nuevoInteresado']);
        Route::get('listarInteresados/{fecha}', [ExtrasController::class, 'listarInteresados']);
        Route::get('borrarInteresados/{id}', [ExtrasController::class, 'borrarInteresados']);
        Route::get('cambiarLike/{id}/{like}', [ExtrasController::class, 'cambiarLike']);
        Route::get('listRecomendation/{id}', [ExtrasController::class, 'listRecomendation']);
        Route::post('addRecomendation', [ExtrasController::class, 'addRecomendation']);
        Route::post('guardarMembresia', [ExtrasController::class, 'guardarMembresia']);
        Route::post('anularMembresia', [ExtrasController::class, 'anularMembresia']);
        Route::post('reservarCitaDoctor', [ExtrasController::class, 'reservarCitaDoctor']);
        Route::post('respuestaInteresado', [ExtrasController::class, 'respuestaInteresado']);
        Route::get('buscarMembresias/{id}', [ExtrasController::class, 'buscarMembresias']);
        Route::get('pedirCitasMembresia/{id}', [ExtrasController::class, 'pedirCitasMembresia']);
        Route::post('ampliarFechaMembresia', [ExtrasController::class, 'ampliarFechaMembresia']);
        Route::post('crearAcontecimiento', [ExtrasController::class, 'crearAcontecimiento']);
        Route::get('cargarLineas/{id}', [ExtrasController::class, 'cargarLineas']);
        Route::get('cambiarEstadoMembresia/{id}/{estado}/{congelar}', [ExtrasController::class, 'cambiarEstadoMembresia']);
        Route::post('insertarSeguimiento', [ExtrasController::class, 'insertarSeguimiento']);
        Route::get('pedirHistorialSeguimientos/{id}', [ExtrasController::class, 'pedirHistorialSeguimientos']);
    });

    // ── PROFESSIONALS & SCHEDULES ─────────────────────────────────────────────
    Route::resource('professional', ProfessionalController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('schedule', ScheduleController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::get('horario/{id}', [ScheduleController::class, 'getschedules']);
    Route::get('horarioLibre/{id}/{fecha}', [ScheduleController::class, 'horarioLibre']);
    Route::get('horarioOcupado/{id}/{fecha}', [ScheduleController::class, 'horarioOcupado']);
    Route::get('horarioCuadernoOcupado/{fecha}/{dia}', [ScheduleController::class, 'horarioCuadernoOcupado']);
    Route::get('/horasLibres/{id}/{fecha}', [ProfessionalController::class, 'horasLibres']);

    // ── PRESCRIPTIONS ─────────────────────────────────────────────────────────
    Route::resource('prescription', PrescriptionController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::post('agregarPrescription', [PrescriptionController::class, 'agregarPrescription']);
    Route::get('allPrescriptionPatient/{id}', [PrescriptionController::class, 'allPrescriptionsConsult']);
    Route::get('kairoPrescription/{date}', [PrescriptionController::class, 'kairoPrescription']);
    Route::get('kairoDelete/{id}', [PrescriptionController::class, 'kairoDelete']);
    Route::get('verRecetaPorId/{id}', [PrescriptionController::class, 'verRecetaPorId']);
    Route::get('pdf/{id}', [PrescriptionController::class, 'createPdf']);

    // ── MEDICAL RECORDS ───────────────────────────────────────────────────────
    Route::resource('initialPsychiatric', Initial_psychiatric_historyController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('initialPsychological', Initial_psychological_historyController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('medicalExam', Medical_examController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::post('newExam', [Medical_examController::class, 'newExam']);
    Route::resource('evolution', Medical_evolutionController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::get('pdfEvolution/thorough/{id}', [Medical_evolutionController::class, 'pdfEvolutionCompleto']);
    Route::get('pdfEvolution/restricted/{id}', [Medical_evolutionController::class, 'pdfEvolutionRestringido']);
    Route::get('pdf_exam/{id}', [Medical_examController::class, 'createPdf']);
    Route::put('authEvol', [Medical_evolutionController::class, 'authEvol']);
    Route::post('editEvolution', [Medical_evolutionController::class, 'editEvolution']);

    // ── EXAMS ─────────────────────────────────────────────────────────────────
    Route::resource('src', ScrController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('burns', BurnController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('millon', MillonController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('zungAns', Zung_anxietyController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('zungDep', Zung_depressionController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('gad', GadController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('exam', ExamController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::post('saveScr', [ScrController::class, 'store']);
    Route::post('saveMillon', [MillonController::class, 'store']);
    Route::post('saveBurns', [BurnController::class, 'store']);
    Route::post('saveZungAns', [Zung_anxietyController::class, 'store']);
    Route::post('saveZungDep', [Zung_depressionController::class, 'store']);
    Route::post('phq', [PhqController::class, 'store']);
    Route::get('cies/{code}', [CieController::class, 'show']);

    // ── MISC ──────────────────────────────────────────────────────────────────
    Route::resource('kairos', KairoController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('limbos', LimboController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('acuerdos', AcuerdoController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::resource('blog', App\Http\Controllers\BlogController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
    Route::post('saveAditional', [AdittionalController::class, 'saveAditional']);
    Route::get('allAditionals', [AdittionalController::class, 'allAditionals']);
    Route::get('/listarMonedas', [SimpleController::class, 'listarMonedas']);
    Route::post('/cambiarMuerte', [SimpleController::class, 'cambiarMuerte']);
    Route::post('/cambiarPersonaActivo', [SimpleController::class, 'cambiarPersonaActivo']);
    Route::post('/guardarSeguimiento', [SimpleController::class, 'guardarSeguimiento']);
    Route::post('/pedirResultadosExamen', [SimpleController::class, 'pedirResultadosExamen']);
    Route::get('/buscarDni/{dni}', [SimpleController::class, 'buscarDni']);
    Route::get('/buscarRUC/{ruc}', [SimpleController::class, 'buscarRUC']);
    Route::post('/crearNutricionPrimera', [SimpleController::class, 'crearNutricionPrimera']);
    Route::post('/listarNutriciones', [SimpleController::class, 'listarNutriciones']);
});
