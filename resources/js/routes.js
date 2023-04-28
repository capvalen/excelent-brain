// 1. Login
const Login = () => import('./components/login/Login.vue')


// 2. Homepage
const HomePage = () => import('./components/HomePage.vue');



//  SUPERUSUARIO
const MainSuperUser = () => import('./components/administrador/Main.vue');


// --- ADMINISTRADOR
const HomeAdministrador = () => import('./components/administrador/views/HomeAdministrador.vue');
const ShowProfessional = () => import('./components/administrador/views/ShowProfessional.vue');

const HomeAdministradorRouterReportes = () => import('./components/administrador/views/reportes/HomeRouterReportes.vue');
const HomeAdministradorReportes = () => import('./components/administrador/views/reportes/HomeReportes.vue');
const HomeAdministradorReporteMedicamentos = () => import('./components/administrador/views/reportes/ReporteMedicamentos.vue')
const HomeAdministradorReportePacientes = () => import('./components/administrador/views/reportes/ReportePacientes.vue')
const HomeReporteMensual = () => import('./components/administrador/views/reportes/ReporteMensual.vue')
const ChartMensual = () => import('./components/administrador/views/reportes/ChartMensual.vue')

// --- PROFESIONAL
const HomeProfesional = () => import('./components/profesional/views/Home.vue');
const HomeProfesionalConsultas = () => import('./components/profesional/components/consultas/HomeConsultas.vue')
const HomeProfesionalEvoluciones = () => import('./components/profesional/components/consultas/EvolucionesPage.vue')
const HomeProfesionalStories = () => import('./components/profesional/components/historia/HomeStories.vue')
const HomeProfesionalRecursos = () => import('./components/profesional/components/recursos/HomeRecursos.vue')
const HomeProfesionalRecetas = () => import('./components/profesional/components/recetas/HomeRecetas.vue')
const HomeProfesionalExamenes = () => import('./components/profesional/components/examenes/HomeExamen.vue')
const Scr90 = () => import('./components/profesional/components/examenes/Scr.vue')
const Burns = () => import('./components/profesional/components/examenes/Burns.vue')
const DepZung = () =>import('./components/profesional/components/examenes/ZungDep.vue')
const AnsZung = () =>import('./components/profesional/components/examenes/ZungAns.vue')
const Millon = () =>import('./components/profesional/components/examenes/Millon.vue')
const phq = () => import('./components/profesional/components/examenes/PHQ-9.vue')
const gad = () => import('./components/profesional/components/examenes/GAD-7.vue')
const bdi = () => import('./components/profesional/components/examenes/BDI-2.vue')
const mcmi = () => import('./components/profesional/components/examenes/MCMI-II.vue')
const baron = () => import('./components/profesional/components/examenes/baron.vue')
const eysenck_a = () => import('./components/profesional/components/examenes/Eysenck-A.vue')
const eysenck_b = () => import('./components/profesional/components/examenes/Eysenck-B.vue')
const srq = () => import('./components/profesional/components/examenes/SRQ.vue')
const phq15 = () => import('./components/profesional/components/examenes/PHQ-15.vue')
const mdq = () => import('./components/profesional/components/examenes/MDQ.vue')

const PrintReceta = () => import('./components/profesional/components/recetas/PrintReceta.vue')
const KairosTable = () => import('./components/profesional/components/recetas/KairosTable.vue')

const HomeKurame = () => import('./components/profesional/components/kurame/HomeKurame.vue')

// --- INTERNO

const HomeInterno = () => import('./components/interno/Home.vue');
const HomePacientes = () => import('./components/interno/HomePacientes.vue');

// --- RECPCIONISTA
const MainRecepcionista = () => import('./components/recepcionista/Main.vue');
const HomeRecepcionista = () => import('./components/recepcionista/components/Home.vue');
const HomeRecepcionistaProfesional = () => import('./components/recepcionista/components/profesionales/HomeProfesional.vue');
const HomeRecepcionistaPacientes = () => import('./components/recepcionista/components/pacientes/HomePacientes.vue');
const HomeRecepcionistaReportes = () => import('./components/recepcionista/components/reportes/HomeReportes.vue');
const HomeRecepcionistaPagos = () => import('./components/recepcionista/components/pagos/HomePagos.vue')
const HomeCont = () => import('./components/recepcionista/components/continuantes/HomeCont.vue')
const HomeAdicionales = () => import('./components/recepcionista/components/adicionales/HomeAdicionales.vue')
const HomeRecordatorios = () => import('./components/recepcionista/components/recordatorios/HomeRecordatorios.vue')


const Voice = () => import('./components/profesional/components/consultas/Voice.vue')

// Pagina no encontrada
const NotFound = () => import('./components/layout/NotFound.vue')

const PatientTest = () => import('./components/PatientTest.vue')

export const routes = [
  {
    path: '/',
    name: 'home',
    component: HomePage,
    meta: {requiresAuth:true},
  },
  {
    path: '/patient-test',
    name: 'patient-test',
    component: PatientTest,
    meta: {requiresAuth:false}
  },
  /* {
    path: '/voice',
    name: 'home',
    component: Voice
  }, */
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {guest:true}
  },
  {
    path: '/receta',
    name: 'receta',
    component: PrintReceta,
    meta: {requiresAuth:true}
  },
  {
    path: '/interno',
    name: 'homeInterno',
    component: HomePage,
    meta: {requiresAuth:true},
    children: [
      {
        path: 'home',
        name: 'HomeInterno',
        component: HomeInterno
      },
			{
        path: 'pacientes',
        name: 'HomePacientes',
        component: HomePacientes
      },
    ]
  },
  {
    path: '/profesional',
    name: 'mainProfesional',
    component: HomePage,
    meta: {requiresAuth:true},
    children: [
      {
        path: 'home',
        name: 'homeProfesional',
        component: HomeProfesional
      },
      {
        path: 'consultas',
        name: 'consultasProfesional',
        component: HomeProfesionalConsultas
      },
      {
        path: 'evoluciones/:idPaciente',
        name: 'evoluciones',
        props: true,
        component: HomeProfesionalEvoluciones
      },
      {
        path: 'historias',
        name: 'historiasProfesional',
        component: HomeProfesionalStories
      },
      {
        path: 'recetas/:patientId',
        name: 'recetas',
        component: HomeProfesionalRecetas
      },
      {
        path:'kairosTable',
        name:'kairosTable',
        component: KairosTable
      },
      {
        path: 'recursos',
        name: 'recursosProfesional',
        component: HomeProfesionalRecursos
      },
      {
        path: 'scr',
        name: 'scr',
        component: Scr90
      },
      {
        path: 'burns',
        name: 'burns',
        component: Burns
      },
      {
        path: 'depzung',
        name: 'depzung',
        component: DepZung
      },
      {
        path: 'anszung',
        name: 'anszung',
        component: AnsZung
      },
      {
        path: 'millon',
        name: 'millon',
        component: Millon
      },
      {
        path: 'phq-9',
        name: 'phq-9',
        component: phq,
      },
      {
        path: 'gad-7',
        name: 'GAD-7',
        component: gad,
      },
      {
        path: 'bdi-2',
        name: 'BDI-2',
        component: bdi,
      },
      {
        path: 'mcmi',
        name: 'MCMI-II',
        component: mcmi,
      },
      {
        path: 'baron',
        name: 'BARON',
        component: baron,
      },
      {
        path: 'eysenck-a',
        name: 'Eysenck-A',
        component: eysenck_a,
      },
      {
        path: 'eysenck-b',
        name: 'Eysenck-B',
        component: eysenck_b,
      },
      {
        path: 'srq',
        name: 'SRQ',
        component: srq,
      },
      {
        path: 'phq-15',
        name: 'phq-15',
        component: phq15
      },
      {
        path: 'mdq',
        name: 'mdq',
        component: mdq
      },
      {
        path: 'examenes/:consultId',
        name: 'examenesProfesional',
        component: HomeProfesionalExamenes
      },
      {
        path: 'kurame',
        name: 'kurame',
        component: HomeKurame
      }
    ]
  },
  {
    path: '/administrador',
    name: 'mainSuperUser',
    component: HomePage,
    children: [
      {
        path: '/administrador/home',
        component: HomeAdministrador
      },
      {
        path: '/administrador/profesional/:profId',
        name: 'showAdminProf',
        component: ShowProfessional
      },
      {
        path: '/administrador/reportes',
        name: 'HomeAdministradorRouterReportes',
        component: HomeAdministradorRouterReportes,
        children: [
          {
            path: 'total',
            name: 'HomeAdministradorReportes',
            component: HomeAdministradorReportes
          },
          {
            path: 'medicamentos',
            name: 'HomeAdministradorReporteMedicamentos',
            component: HomeAdministradorReporteMedicamentos
          },
          {
            path: 'pacientes',
            name: 'HomeAdministradorReportePacientes',
            component: HomeAdministradorReportePacientes
          },
          {
            path: 'mensual',
            name: 'HomeReporteMensual',
            component: HomeReporteMensual
          },
          {
            path: 'chart',
            name: 'ChartMensual',
            component: ChartMensual
          }
        ]
      }
    ]
  },
  {
     path: '/recepcionista',
     name: 'main',
     component: HomePage,
     meta: {requiresAuth:true},
     children: [
        {
          path: 'home',
          name: 'homeRecepcionista',
          component: HomeRecepcionista
        },
        {
          path: 'profesionales',
          name: 'profesionalRecepcionista',
          component: HomeRecepcionistaProfesional
        },
        {
          path: 'pacientes',
          name: 'pacientesRecepcionista',
          component: HomeRecepcionistaPacientes
        },
        {
          path: 'reportes',
          name: 'reportesRecepcionista',
          component: HomeRecepcionistaReportes
        },
        {
          path:'continuantes',
          name:'continuantesRecepcionista',
          component: HomeCont
        },
        {
          path:'adicionales',
          name: 'adicionalesRecepcionista',
          component: HomeAdicionales
        },
        {
          path:'pagos',
          name: 'pagosRecepcionista',
          component: HomeRecepcionistaPagos
        },
				{
          path:'recordatorios',
          name: 'recordatorios',
          component: HomeRecordatorios
        }
      ]
  },
  {
    path: '/:pathMedia(.*)',
    name: "NotFound",
    component: NotFound
  },
]
