<template>
	<div class="container-fluid p-2">
		<div class="row">
			
			<div class="col-md-4 col-lg-3">
				<label for="">Estas visualizando datos de </label>
				<input type="date" class="form-control" v-model="fecha">
			</div>

			<div class="table-responsive mt-2">
				<table class="table table-hover table-bordered" >
					<thead class="sticky-top">
						<tr>
							<th>Hora</th>
							<th v-for="profesional in profesionales">{{ profesional.nombre }}</th>
						</tr>
					</thead>
					<tbody id="tbodyHorasProf">
						<!-- <tr v-for="hora in horas" :id="hora">
							<td >{{ horaNormal(hora) }}</td>
							<td class="inactivo" v-for="campo in celdas"></td>
						</tr> -->
						<tr v-for="espacio in espaciosHoy" :id="espacio.hora">
							<td>{{ horaNormal(espacio.hora) }}</td>
							<td v-for="campo in espacio.profesionales " :class="queEsta(campo.indexProf, espacio.hora)"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>
<script>
import moment from 'moment'
export default{
	name: 'VistaCalendario',
	props:['profesionales'],
	data(){ return{
		fecha: moment().format('YYYY-MM-DD'), horas:[], celdas: [],
		dias:{ 0: 'Domingo', 1: 'Lunes', 2: 'Martes', 3: 'Miercoles', 4: 'Jueves', 5:'Viernes', 6:'Sabado' }, hoyLetras:null,
		espaciosHoy:[]
	}},
	mounted(){

		for(let i=8; i<=20; i++){
			this.horas.push(i)
		}

	},
	watch:{
		profesionales(){
			this.horasDeProfesional();
		}
	},
	methods:{
		horasDeProfesional(){
			
			this.hoyLetras = this.dias[moment(this.fecha, 'YYYY-MM-DD').format('d')]// this.dias[]

			var contador=0;
			for(let i=8; i<=20; i++){
				this.espaciosHoy.push({hora: i, profesionales:[]})
				
				this.profesionales.forEach((prof, index)=>{
					this.espaciosHoy[contador].profesionales.push({
						idProf: prof.id, nombre: prof.name, indexProf:index, indexHorario:i
					})
				})
				contador++;
			}
			//console.log(this.espaciosHoy)

			//this.espaciosHoy = this.profesionales
			/* this.profesionales.forEach(prof => this.espaciosHoy.push(prof.schedules.filter(z=> z.day == this.hoyLetras )))
			console.log('espaciosHoy');
			console.log(this.espaciosHoy);

			var horarioTemp
			this.profesionales.forEach((prof, index)=>{
				
				horarioTemp = this.espaciosHoy[index]
				
				for(let i=8; i<=20; i+=0.5){
					horarioTemp.forEach(h=>{
						if(h.check_time== i+':00:00'  ){ this.celdas.push({hora: i, tipo:'trabaja' })
						}else if(h.check_time== i+':15:00'  ){ this.celdas.push({hora: i, tipo:'trabaja' })
						}else if(h.check_time== i+':30:00'  ){this.celdas.push({hora: i, tipo:'trabaja' })
						}else if(h.check_time== i+':45:00'  ){this.celdas.push({hora: i, tipo:'trabaja' })
						}else{ //No trabaja
							this.celdas.push({hora: i, tipo:'libre' })
						}

					})
				}
			}) */

		},
		horaNormal(horita){
			let temporal = null
			if(horita%1 ==0) temporal =  horita+':00';
			else temporal = Math.floor(horita)+':30'
			return moment(temporal, 'HH:mm').format('hh:mm a')
		},
		queEsta( indexProf, hora){
			var queEs = this.profesionales[indexProf].schedules.find(x=> x.day == this.hoyLetras && x.check_time ==hora+':00:00' || x.check_time == hora+':15:00' || x.check_time ==hora+':30:00' || x.check_time ==hora+':45:00' )

			if(queEs) return 'libre' 
			else return 'inactivo'
		}
	}
}</script>
<style>
.inactivo{background-color: #f8f9fa!important;}
.noTrabaja{background-color: #8a09b5!important;}
.libre{background-color: #8cfeaa!important;}
</style>