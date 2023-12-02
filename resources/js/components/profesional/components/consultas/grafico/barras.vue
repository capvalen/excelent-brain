<template>
  <canvas id="myChart" width="400" height="200"></canvas>

</template>

<script>
import Chart from 'chart.js/auto';
import moment from 'moment';

export default{
	name: 'myChart',
	props:['citas', 'medical_evolutions'],
	data(){ return {
		etiquetas:['psi', 'psco', 'otro'], datos:{'Psiquiatría':0, 'Psicología':0, 'Otros': 0}, bandera:[], datosPsico:[], datosPsy:[],
		evolucionPsiquiatria: [1,2,3,4,5,6,16,17], //Ver tabla de precios, son los IDs
		evolucionPsicologia: [7,8,9,10,11,12,18,22,27], //Ver tabla de precios, son los IDs
	}},
	methods:{
		dondeEsta2(tips){
			let valor = null
			if (valor = this.evolucionPsiquiatria.indexOf(tips)>-1) return 'Psiquiatría'
			else if (valor = this.evolucionPsicologia.indexOf(tips)>-1) return 'Psicología'
			else return 'Otros'
		},
	},
	mounted(){

		this.medical_evolutions.forEach(cita=>{
			console.log('quetipoEs', this.dondeEsta2(cita.type))
			this.datos[this.dondeEsta2(cita.type)]+=1;
		})

		
	/* 	this.evolucionPsicologia.forEach(cita=>{
			let año = moment(cita.date).format('YYYY')
			if( this.etiquetas.indexOf(año) >-1 ){ //ya tiene
				let queIndice = this.etiquetas.indexOf(año)
				this.datosPsico[queIndice] +=1 
			}else{
				//this.etiquetas.push(año);
				this.datosPsico.push(1)
			}
			//console.log( indice,  this.etiquetas.indexOf(año) )

		})
		this.evolucionPsiquiatria.forEach(cita=>{
			let año = moment(cita.date).format('YYYY')
			if( this.etiquetas.indexOf(año) >-1 ){ //ya tiene
				let queIndice = this.etiquetas.indexOf(año)
				this.datosPsy[queIndice] +=1 
			}else{
				//this.etiquetas.push(año);
				this.datosPsy.push(1)
			}
		}) */
		
		const ctx = document.getElementById('myChart').getContext('2d');
		const myChart = new Chart(ctx, {
				type: 'bar',
				data: {
						//labels: this.etiquetas,
						datasets: [
							{
								label: 'N° de visitas',
								data: this.datos,
								backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
						},

					]
				},
				options: {
						scales: {
								y: {
										beginAtZero: true
								}
						}
				}
		});
	}
}
</script>