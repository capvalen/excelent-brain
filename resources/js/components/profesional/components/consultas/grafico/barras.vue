<template>
  <canvas id="myChart" width="400" height="200"></canvas>

</template>

<script>
import Chart from 'chart.js/auto';
import moment from 'moment';

export default{
	name: 'myChart',
	props:['citas'],
	data(){ return {
		etiquetas:[], datos:[], bandera:[]
	}},
	mounted(){

		
		this.citas.forEach(cita=>{
			let año = moment(cita.date).format('YYYY')
			
			/* let existe = this.bandera.filter( x => moment(x.date).format('YYYY') == cita.año && x.clasification==cita.clasification)
			console.log('existe', existe);
			if(existe){
				this.bandera[existe].count +=1;
			}else{
				this.bandera.push({
					year: x.clasification==1? 'PSI':'PSCO'+ cita.año,
					count:1
				})
			} */
			
			
			let indice= -1;
			if( this.etiquetas.indexOf(año) >-1 ){ //ya tiene
				let queIndice = this.etiquetas.indexOf(año)
				this.datos[queIndice] +=1 
			}else{
				this.etiquetas.push(año);
				this.datos.push(1)
			}
			//console.log( indice,  this.etiquetas.indexOf(año) )

		})
		
		const ctx = document.getElementById('myChart').getContext('2d');
		const myChart = new Chart(ctx, {
				type: 'bar',
				data: {
						labels: this.etiquetas,
						datasets: [{
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
						}]
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