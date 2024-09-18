<template>
	<main>
		<h1>SCL-90R <span v-if="verResultados">- RESULTADOS</span></h1>

		<div class="scrollable" v-if="!verResultados">
			Somatizacion:  {{this.somat}}
			<br>
			Obsesion- Compulsion: {{this.obses}}
			<br>
			Sensibilidad interpersonal: {{this.sensib}}
			<br>
			Depresión: {{this.depre}}
			<br>
			Ansiedad: {{this.ansi}}
			<br>
			Hostilidad: {{this.hostil}}
			<br>
			Ansiedad fóbica: {{this.ansi_fob}}
			<br>
			Ideación paranoide: {{this.paranoid}}
			<br>
			Psicoticismo:   {{this.psicot}}
			<br>
			IGS: {{this.igs}}
			<br>
			SP: {{this.sp}}
			<br>
			PSDI: {{this.psdi}}
			<br>
			<button v-if="!verResultados" class="btn btn-primary" @click="sumatoria">Calcular y guardar</button>
		</div>

		<div v-if="!verResultados">
			<label for="">Nombre del Paciente</label>
			<div class="d-flex gap flex-wrap">
				<input type="text" name="" v-model="buscar" id="" class="form-control input-name">
				 <div v-show="showResults" class="border border-secondary shadow w-100">
					<div @click="selectPatient(patient)" class="border border-secondary"  v-for="patient in filtro" :key="patient.id">{{patient.name}} {{patient.nombres}}</div>
				</div>
				<select v-model="sexo" name="" id="" class="form-select input-select">
					<option value="">Sexo</option>
					<option value="M">Varon</option>
					<option value="F">Mujer</option>
				</select>
			</div>
		</div>

		<p class="mt-2">
		   ¿HASTA QUE PUNTO SE HA SENTIDO AFECTADO POR:?
		   Utilice los siguientes criterios:
		   <br>
		   A: Nada B: Un poco C: Moderadamente D: Bastante E: Mucho
		</p>

		<ul class="p-0">
			<li v-for="item in items" :key="item.id">
			   {{item.id}}. {{item.content}}
			   <br>
			   <div v-if="!verResultados">
					<div class="form-check form-check-inline" @click="inputValue(item.id)">
										 <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-a`" value="0">
										 <label class="form-check-label" :for="`inlineRadio${item.id}-a`">A</label>
					</div>
					<div class="form-check form-check-inline" @click="inputValue(item.id)">
										 <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-b`" value="1">
										 <label class="form-check-label" :for="`inlineRadio${item.id}-b`">B</label>
					</div>
					<div class="form-check form-check-inline" @click="inputValue(item.id)">
										 <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-c`" value="2">
										 <label class="form-check-label" :for="`inlineRadio${item.id}-c`">C</label>
					</div>
					<div class="form-check form-check-inline" @click="inputValue(item.id)">
										 <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-d`" value="3">
										 <label class="form-check-label" :for="`inlineRadio${item.id}-d`">D</label>
					</div>
					<div class="form-check form-check-inline" @click="inputValue(item.id)">
										 <input class="form-check-input" type="radio" :name="`inlineRadioOptions${item.id}`" :id="`inlineRadio${item.id}-e`" value="4">
										 <label class="form-check-label" :for="`inlineRadio${item.id}-e`">E</label>
					</div>
				 </div>
				 <div v-else>
					<small class="text-dark">Marcado = {{queRespuesta(item.id)}}</small>
				 </div>
			</li>
		</ul>

		<button class="btn btn-success" @click="print">Imprimir Respuestas</button>
	</main>
</template>

<script>
	export default {
		data(){
			return{
				items:[
				{id:'1',content: 'Dolores de cabeza'},
				{id:'2',content: 'Nerviosismo o agitación interior'},
				{id:'3',content: 'Pensamientos, palabras o ideas no deseadas que no se le van de su mente'},
				{id:'4',content: 'Sensaciones de desmayo o mareo'},
				{id:'5',content: 'Pérdida del deseo o del placer sexual'},
				{id:'6',content: 'El hecho de juzgar a otras personas crítica o negativamente'},
				{id:'7',content: 'La idea de que otra persona puede controlar sus pensamientos'},
				{id:'8',content: 'La impresión de que la mayoría de sus problemas son culpa de los demá'},
				{id:'9',content: 'Dificultad para recordar las cosas'},
				{id:'10',content: 'Preocupaciones acerca del desaseo, el descuido o la desorganización'},
				{id:'11',content: 'Sentirse fácilmente irritado o enfadado'},
				{id:'12',content: 'Dolores en el corazón o en el pecho'},
				{id:'13',content: 'Sentir miedo de los espacios abiertos o de la calle'},
				{id:'14',content: 'Sentirse bajo de energías o decaído'},
				{id:'15',content: 'Pensamientos o ideas de acabar con su vida'},
				{id:'16',content: 'Oír voces que otras personas no oyen'},
				{id:'17',content: 'Temblores'},
				{id:'18',content: 'La idea de que no se puede fiar de la mayoría de las personas'},
				{id:'19',content: 'Falta de apetito'},
				{id:'20',content: 'Llorar fácilmente'},
				{id:'21',content: 'Timidez o incomodidad con el sexo opuesto'},
				{id:'22',content: 'La sensación de estar atrapado o como encerrado'},
				{id:'23',content: 'Tener miedo de repente y sin razón'},
				{id:'24',content: 'Arrebatos de cólera o ataques de furia que no logra controlar'},
				{id:'25',content: 'Miedo a salir de casa solo'},
				{id:'26',content: 'Culparse a sí mismo de lo que pasa'},
				{id:'27',content: 'Dolores en la parte baja de la espalda'},
				{id:'28',content: 'Sentirse incapaz de lograr cosas'},
				{id:'29',content: 'Sentirse solo'},
				{id:'30',content: 'Sentirse triste'},
				{id:'31',content: 'Preocuparse demasiado por las cosas'},
				{id:'32',content: 'No sentir interés por las cosas'},
				{id:'33',content: 'Sentirse temeroso'},
				{id:'34',content: 'Ser demasiado sensible o sentirse herido con facilidad'},
				{id:'35',content: 'La impresión de que los demás se dan cuenta de sus pensamientos'},
				{id:'36',content: 'La sensación de que los demás no le comprenden o no le hacen caso'},
				{id:'37',content: 'La impresión de que la gente es poco amistosa o que usted no les gusta'},
				{id:'38',content: 'Tener que hacer las cosas muy despacio para estar seguro de que las hace bien'},
				{id:'39',content: 'Que el corazón le palpita o le vaya muy deprisa'},
				{id:'40',content: 'Náuseas o malestar en el estómago'},
				{id:'41',content: 'Sentirse inferior a los demás '},
				{id:'42',content: 'Dolores musculares '},
				{id:'43',content: 'Sensación de que otras personas le miran o hablan de usted '},
				{id:'44',content: 'Dificultad para conciliar el sueño '},
				{id:'45',content: 'Tener que comprobar una y otra vez lo que hace'},
				{id:'46',content: 'Encontrar difícil el tomar decisiones '},
				{id:'47',content: 'Sentir temor a viajar en coches, autobuses, metro, trenes, etc. '},
				{id:'48',content: 'Dificultad para respirar '},
				{id:'49',content: 'Sentir calor o frío de repente '},
				{id:'50',content: 'Tener que evitar ciertas cosas, lugares o actividades por que le dan miedo '},
				{id:'51',content: 'Que se le quede la mente en blanco '},
				{id:'52',content: 'Entumecimiento u hormigueo en alguna parte del cuerpo '},
				{id:'53',content: 'Sentir un nudo en la garganta'},
				{id:'54',content: 'Sentirse desesperanzado con respecto al futuro '},
				{id:'55',content: 'Tener dificultad para concentrarse '},
				{id:'56',content: 'Sentirse débil en alguna parte del cuerpo '},
				{id:'57',content: 'Sentirse tenso o agitado '},
				{id:'58',content: 'Pesadez en los brazos o las piernas'},
				{id:'59',content: 'Pensamientos sobre la muerte o el hecho de morir'},
				{id:'60',content: 'El comer demasiado '},
				{id:'61',content: 'Sentirse incomodo cuando la gente le mira o habla acerca de usted '},
				{id:'62',content: 'Tener pensamientos que no son suyos'},
				{id:'63',content: ' Sentir el impulso de golpear, herir o hacer daño a alguien'},
				{id:'64',content: 'Despertarse de madrugada'},
				{id:'65',content: 'Tener que repetir las mismas acciones tales como tocar, contar, lavar, etc.'},
				{id:'66',content: 'Sueño inquieto o desvelarse fácilmente'},
				{id:'67',content: 'Tener fuertes deseos de romper algo'},
				{id:'68',content: 'Tener ideas o creencias que los demás no comparten'},
				{id:'69',content: 'Sentirse muy cohibido entre otras personas'},
				{id:'70',content: 'Sentirse muy incómodo entre mucha gente, p.ej. en el cine, en las tiendas'},
				{id:'71',content: 'Sentir que todo requiere un gran esfuerzo'},
				{id:'72',content: 'Ataques de terror o pánico'},
				{id:'73',content: 'Sentirse incómodo comiendo o bebiendo en público '},
				{id:'74',content: 'Tener discusiones frecuentes'},
				{id:'75',content: 'Sentirse nervioso cuando se encuentra solo'},
				{id:'76',content: 'El que otros no reconozcan adecuadamente sus logros'},
				{id:'77',content: 'Sentirse solo aunque esté con más gente'},
				{id:'78',content: 'Sentirse tan inquieto que no puede ni estar sentado tranquilo'},
				{id:'79',content: 'La sensación de ser inútil o de no valer nada'},
				{id:'80',content: 'Pensamientos de que va a pasar algo malo '},
				{id:'81',content: 'Tener deseos de gritar o de tirar cosas'},
				{id:'82',content: 'Tener miedo de desmayarse en público'},
				{id:'83',content: 'La impresión de que la gente intentaría aprovecharse de usted si les dejara'},
				{id:'84',content: 'Tener pensamientos sobre el sexo que le inquietan bastante'},
				{id:'85',content: 'La idea de que debería ser castigado por sus pecados'},
				{id:'86',content: 'Pensamientos o imágenes estremecedoras que le dan miedo'},
				{id:'87',content: 'La idea de que algo anda mal en su cuerpo'},
				{id:'88',content: 'No sentirse cercano o íntimo con nadie'},
				{id:'89',content: 'Sentimientos de culpabilidad '},
				{id:'90',content: 'La idea de que algo anda mal en su mente'}
				],
				somat:0,
				obses:0,
				sensib:0,
				depre:0,
				ansi:0,
				hostil:0,
				ansi_fob:0,
				paranoid:0,
				psicot:0,
				igs:0,
				sp:0,
				psdi:0,
				objs:[],
				sexo:'',
				buscar:'',
				showResults:false,
				patients:[],
				patient_id :'', verResultados: false
			}
		},

		methods:{
			inputValue(nro){
				var name = 'inlineRadioOptions'+nro
				var rpta = document.querySelector('input[name='+name+']:checked').value;
				var obj = {nro, rpta}

				if(this.verifDuplicated(obj)){

					this.objs.push({...obj})
				}else{

				}
				//console.log(this.objs)

				/* var filter = this.objs.filter(obj => obj.nro == nro)
				console.log(filter)
				if(filter.lenght != 0){
					this.objs.splice(obj,1)
					obj= {nro, rpta}
					this.objs.push({...obj})
				}else{

				}

				console.log(this.objs) */
			},
			verifDuplicated(obj2){
				if(this.objs.filter(obj=>obj.nro == obj2.nro).length == 0){
					return true
				}else{
					console.log('coincidencia de nro')
					this.objs = this.objs.filter(obj=>obj.nro != obj2.nro)
					this.objs.push({...obj2})
					return false
				}
			},
			sumatoria(){
				this.somat=0,
				this.obses=0,
				this.sensib=0,
				this.depre=0,
				this.ansi=0,
				this.hostil=0,
				this.ansi_fob=0,
				this.paranoid=0,
				this.psicot=0,
				this.igs = 0,
				this.sp = 0

				this.objs.forEach((value, index)=>{

				var nro = value.nro
				var rpta = value.rpta
				this.igs += parseInt(rpta)

				if(rpta != 0){
					this.sp += 1
				}

				if(nro == 1 || nro == 4 || nro == 12 || nro == 27 || nro == 40 || nro == 42 || nro == 48 || nro == 49 || nro == 52 || nro == 53 || nro == 56 || nro == 58 ){
					this.somat += parseInt(rpta)
				}else if(nro == 3 || nro == 9 || nro == 10 || nro == 28 || nro == 38 || nro == 45 || nro == 46 || nro == 51 || nro == 55 || nro == 65){
					this.obses += parseInt(rpta)
				}else if(nro == 6 || nro == 21 || nro == 34 || nro == 36 || nro == 37 || nro == 41 || nro == 61 || nro == 69 || nro == 73){
					this.sensib += parseInt(rpta)
				}else if(nro == 5 || nro == 14 || nro == 15 || nro == 20 || nro == 22 || nro == 26 || nro == 29 || nro == 30 || nro == 31 || nro == 32 || nro == 54 || nro == 71 || nro == 79){
					this.depre += parseInt(rpta)
				}else if(nro == 2 || nro == 17 || nro == 23 || nro == 33 || nro == 39 || nro == 57 || nro == 72 || nro == 78 || nro == 80 || nro == 86){
					this.ansi += parseInt(rpta)
				}else if(nro == 74 || nro == 81 || nro == 67 || nro == 63 || nro == 24 || nro == 11){
					this.hostil += parseInt(rpta)
				}else if(nro == 13 || nro == 25 || nro == 47 || nro == 50 || nro == 75 || nro == 82){
					this.ansi_fob += parseInt(rpta)
				}else if(nro == 8 || nro == 18 || nro == 43 || nro == 68 || nro == 76 || nro == 83){
					this.paranoid += parseInt(rpta)
				}else if(nro == 7 || nro == 16 || nro == 35 || nro == 62 || nro == 77 || nro == 84 || nro == 85 || nro == 87|| nro == 88 || nro == 90){
					this.psicot += parseInt(rpta)
				}
				})

				this.psdi = this.igs/this.sp
				this.igs = this.igs/90

				this.somat = this.somat/12
				this.obses = this.obses/10
				this.sensib = this.sensib/9
				this.depre = this.depre/13
				this.ansi = this.ansi/10
				this.hostil = this.hostil/6
				this.ansi_fob = this.ansi_fob/6
				this.paranoid = this.paranoid/6
				this.psicot = this.psicot/10

				if(this.sexo == 'M'){
					//Somatizacion
				if(this.somat >=1.49){
					this.somat = 80
				}else if (this.somat >= 0.81){
					this.somat = 65
				}else if (this.somat >= 0.25){
					this.somat = 50
				}else if(this.somat >= 0){
					this.somat = 35
				}
				//Obsesión
				if(this.obses >= 2.51){
					this.obses = 80
				}else if(this.obses >= 1.49){
					this.obses = 65
				}else if(this.obses >= 0.495){
					this.obses = 50
				}else if(this.obses >= 0){
					this.obses = 35
				}
				//Sensibilidad
				if(this.sensib >= 2.025){
					this.sensib = 80
				}else if(this.sensib >= 1.175){
					this.sensib = 65
				}else if(this.sensib >= 0.38){
					this.sensib = 50
				}else if(this.sensib >= 0){
					this.sensib = 35
				}
				//Depresión
				if(this.depre >= 1.775){
					this.depre = 80
				}else if(this.depre >= 1.025){
					this.depre = 65
				}else if(this.depre >= 0.33){
					this.depre = 50
				}else if(this.depre >= 0){
					this.depre = 35
				}
				//Ansiedad
				if(this.ansi >= 1.75){
					this.ansi = 80
				}else if(this.ansi >= 0.95){
					this.ansi = 65
				}else if(this.ansi >= 0.28){
					this.ansi = 50
				}else if(this.ansi >= 0){
					this.ansi = 35
				}
				//Hostilidad
				if(this.hostil >= 2.7){
					this.hostil = 80
				}else if(this.hostil >= 1.5){
					this.hostil = 65
				}else if(this.hostil >= 0.455){
					this.hostil = 50
				}else if(this.hostil >= 0){
					this.hostil = 35
				}
				//Ansiedad Fóbica
				if(this.ansi_fob >= 1.29){
					this.ansi_fob = 80
				}else if(this.ansi_fob >= 0.61){
					this.ansi_fob = 65
				}else if(this.ansi_fob >= 0.14){
					this.ansi_fob = 50
				}else if(this.ansi_fob >= 0){
					this.ansi_fob = 35
				}
				//Ideacion Paranoide
				if(this.paranoid >= 2.4){
					this.paranoid = 80
				}else if(this.paranoid >= 1.3){
					this.paranoid = 65
				}else if(this.paranoid >= 0.38){
					this.paranoid = 50
				}else if(this.paranoid >= 0){
					this.paranoid = 35
				}
				//Psicoticismo
				if(this.psicot >= 1.95){
					this.psicot = 80
				}else if(this.psicot >= 0.75){
					this.psicot = 65
				}else if(this.psicot >= 0.205){
					this.psicot = 50
				}else if(this.psicot >= 0){
					this.psicot = 35
				}
				//IGS
				if(this.igs >= 0.295){
					this.igs = 80
				}else if(this.igs >= 0.165){
					this.igs = 65
				}else if(this.igs >= 0.055){
					this.igs = 50
				}else if(this.igs >= 0){
					this.igs = 35
				}
				//PSDI
				if(this.psdi >= 3.015){
					this.psdi = 80
				}else if(this.psdi >= 2.185){
					this.psdi = 65
				}else if(this.psdi >= 1.355){
					this.psdi = 50
				}else if(this.psdi >= 0){
					this.psdi = 35
				}
				//Sintomas positivos
				if(this.sp >= 73){
					this.sp = 80
				}else if(this.sp >= 53){
					this.sp = 65
				}else if(this.sp >= 33){
					this.sp = 50
				}else if(this.sp >= 0){
					this.sp = 35
				}
				}else{
					//Somatizacion
				if(this.somat >=1.95){
					this.somat = 80
				}else if (this.somat >= 1.25){
					this.somat = 65
				}else if (this.somat >= 0.65){
					this.somat = 50
				}else if(this.somat >= 0){
					this.somat = 35
				}
				//Obsesión
				if(this.obses >= 3.11){
					this.obses = 80
				}else if(this.obses >= 1.89){
					this.obses = 65
				}else if(this.obses >= 0.72){
					this.obses = 50
				}else if(this.obses >= 0){
					this.obses = 35
				}
				//Sensibilidad
				if(this.sensib >= 3){
					this.sensib = 80
				}else if(this.sensib >= 1.81){
					this.sensib = 65
				}else if(this.sensib >= 0.66){
					this.sensib = 50
				}else if(this.sensib >= 0){
					this.sensib = 35
				}
				//Depresión
				if(this.depre >= 3){
					this.depre = 80
				}else if(this.depre >= 1.8){
					this.depre = 65
				}else if(this.depre >= 0.605){
					this.depre = 50
				}else if(this.depre >= 0){
					this.depre = 35
				}
				//Ansiedad
				if(this.ansi >= 2.9){
					this.ansi = 80
				}else if(this.ansi >= 1.75){
					this.ansi = 65
				}else if(this.ansi >= 0.555){
					this.ansi = 50
				}else if(this.ansi >= 0){
					this.ansi = 35
				}
				//Hostilidad
				if(this.hostil >= 3.05){
					this.hostil = 80
				}else if(this.hostil >= 1.75){
					this.hostil = 65
				}else if(this.hostil >= 0.555){
					this.hostil = 50
				}else if(this.hostil >= 0){
					this.hostil = 35
				}
				//Ansiedad Fóbica
				if(this.ansi_fob >= 1.85){
					this.ansi_fob = 80
				}else if(this.ansi_fob >= 0.95){
					this.ansi_fob = 65
				}else if(this.ansi_fob >= 0.255){
					this.ansi_fob = 50
				}else if(this.ansi_fob >= 0){
					this.ansi_fob = 35
				}
				//Ideacion Paranoide
				if(this.paranoid >= 3.05){
					this.paranoid = 80
				}else if(this.paranoid >= 1.735){
					this.paranoid = 65
				}else if(this.paranoid >= 0.54){
					this.paranoid = 50
				}else if(this.paranoid >= 0){
					this.paranoid = 35
				}
				//Psicoticismo
				if(this.psicot >= 2.2){
					this.psicot = 80
				}else if(this.psicot >= 1.2){
					this.psicot = 65
				}else if(this.psicot >= 0.355){
					this.psicot = 50
				}else if(this.psicot >= 0){
					this.psicot = 35
				}
				//IGS
				if(this.igs >= 0.41){
					this.igs = 80
				}else if(this.igs >= 0.25){
					this.igs = 65
				}else if(this.igs >= 0.09){
					this.igs = 50
				}else if(this.igs >= 0){
					this.igs = 35
				}
				//PSDI
				if(this.psdi >= 3.2){
					this.psdi = 80
				}else if(this.psdi >= 2.385){
					this.psdi = 65
				}else if(this.psdi >= 1.555){
					this.psdi = 50
				}else if(this.psdi >= 0){
					this.psdi = 35
				}
				//Sintomas positivos
				if(this.sp >= 73){
					this.sp = 80
				}else if(this.sp >= 53){
					this.sp = 65
				}else if(this.sp >= 33){
					this.sp = 50
				}else if(this.sp >= 0){
					this.sp = 35
				}
				}

				const config = {
					headers: { 'content-type': 'multipart/form-data' }
				}

				let formData = new FormData()
				formData.append('somatization', this.somat)
				formData.append('obsession', this.obses)
				formData.append('interpersonal_sensitivity', this.sensib)
				formData.append('depression', this.depre)
				formData.append('anxiety', this.ansi)
				formData.append('hostility', this.hostil)
				formData.append('phobic_anxiety', this.ansi_fob)
				formData.append('paranoid_ideation', this.paranoid)
				formData.append('psychoticism', this.psicot)
				formData.append('igs', this.igs)
				formData.append('sp', this.sp)
				formData.append('psdi', this.psdi)
				formData.append('patient_id', this.patient_id)
				formData.append('professional_id', this.$attrs.dataUser.id)
				formData.append('resultados', JSON.stringify(this.objs))

				this.axios.post('/api/saveScr', formData, config)
				.then((result) => {
					this.$swal('Resultados regitrados correctamente')
				}).catch((err) => {
					this.$swal({
						icon: 'error',
						title: 'Error...',
						text: err
					})
				});
			},
			print(){
				window.print();
			},
			getNames(){
				this.axios.get('/api/getNames')
				.then((result) => {
					this.patients = result.data
					//console.log(this.patients)
				}).catch((err) => {

				});
			},
			selectPatient(patient){
				this.$swal('Usuario aceptado')
				this.patient_id = patient.id
				this.buscar = patient.name
				this.showResults = false
			},
			queRespuesta(id){
				//buscamos la si tiene respuesta a la pregunta
				const resp = this.objs.filter(x=> x.nro==id)
				console.log('resp' , resp);
				if( resp.length>0 )
					switch (resp[0].rpta) {
						case '0': return 'A: Nada'; break;
						case '1': return 'B: Un poco'; break;
						case '2': return 'C: Moderadamente'; break;
						case '3': return 'D: Bastante'; break;
						case '4': return 'E: Mucho'; break;
						default: return 'Sin respuesta registrada'; break;
					}
				else return 'Sin respuesta registrada';
			}
		},
		computed:{
		filtro(){
			if(!this.buscar){
				this.showResults = false
				return null
			}else{
				this.showResults = true
				return this.patients.filter(patient => patient.name.toLowerCase().includes(this.buscar)).slice(0,6)
			}
		}
		},
		mounted(){
			if(this.$route.params.id){
				this.axios.post('/api/pedirResultadosExamen/',{examen:'scr', id:this.$route.params.id})
				.then(resp=> {
					this.objs = JSON.parse(resp.data.resultados)
				})
				this.verResultados=true;
			}else this.verResultados=false
			this.getNames()
		}
	}
</script>

<style scoped>
	ul{
		list-style: none;
	}

	li{
		background-color: #fff;
		font-size: 20px;
		border: 1px solid rgba(0, 0, 0, 0.493);
		margin: 10px 0px 10px 0px;
		border-radius: 5px;
		padding: 10px;
	}

	.input-name {
		width: 70%;
		flex: 1 0 auto;
		min-width: 250px;
	}

	.input-select {
		width: 25%;
		flex: 1 0 auto;
	}

	.scrollable{
		background-color: #fff;
		color: #000;
		position: fixed;
		right: 0;
		border: 1px solid rgba(0, 0, 0, 0.493);
		z-index:99;
		border-radius: 5px;
		padding: 5px;
	}

	.gap {
		gap: 15px;
	}

	@media screen and (max-width: 750px) {
		.scrollable {
			position: relative;
		}
	}
</style>
