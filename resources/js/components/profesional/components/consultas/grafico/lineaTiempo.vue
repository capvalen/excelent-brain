<template>
	<section class="timeline_area section_padding_130">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 col-sm-8 col-lg-6">
					<!-- Section Heading-->
					<div class="section_heading text-center">
						<h6>Mi línea de vida</h6>
						<h3>Acontecimientos en el paso del tiempo</h3>
						<div class="line"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="apland-timeline-area" v-for="clave in claves">
						<!-- Single Timeline Content-->
						<div class="single-timeline-area">
							<div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s"
								style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
								<p>{{queMes(clave)}}</p>
							</div>
							<div class="row">
								<div class="col-12 col-md-6 col-lg-5" v-for="evento in agrupado[clave]">
									<div class="single-timeline-content d-flex wow fadeInLeft" data-wow-delay="0.3s"
										style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
										<div class="timeline-icon"><i class="fa fa-address-card" aria-hidden="true"></i></div>
										<div class="timeline-text">
											<h6>Acontecimiento: {{evento.acontecimiento}}</h6>
											<p>Síntomas: {{ evento.sintomas }}</p>
											<p>Edad: {{ evento.edad }}</p>
											<p>Profesional: {{ evento.nomProfesional }}</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>
import moment from 'moment'
export default{
	name: 'lineaDeTiempo',
	props:['id'],
	data(){return{
		agrupado:{}, lineas:[], claves:[]
	}},
	mounted(){
		this.$parent.$on('ordenarLineas', this.cargarLineas);

		this.cargarLineas();
	},
	methods:{
		cargarLineas(){
			this.axios('/api/cargarLineas/'+this.id)
			.then(res=> { //console.log(res.data);
				this.lineas=res.data;
				this.ordenar()
			})
		},
		ordenar(){
			this.agrupado={};
			this.lineas.forEach(elemento => {
				const fecha = new Date(elemento.fecha);
				const año = fecha.getFullYear();
				const mes = fecha.getMonth() + 1; // Los meses en JavaScript son 0-indexados, por lo que sumamos 1

				const clave = `${año}-${mes < 10 ? '0' : ''}${mes}`;

				if (!this.agrupado[clave]) {
					this.agrupado[clave] = [];
				}
				this.agrupado[clave].push(elemento);

				this.claves = Object.keys(this.agrupado);
			});
		},
		queMes(mes){
			moment.locale('es')
			return moment(mes, 'YYYY-MM').format('MMMM YYYY')
		}
	},
	computed:{
		
	}

}
</script>

<style scoped>
	body {
		margin-top: 20px;
	}

	.timeline_area {
		position: relative;
		z-index: 1;
	}

	.single-timeline-area {
		position: relative;
		z-index: 1;
		padding-left: 180px;
	}

	@media only screen and (max-width: 575px) {
		.single-timeline-area {
			padding-left: 100px;
		}
	}

	.single-timeline-area .timeline-date {
		position: absolute;
		width: 180px;
		height: 100%;
		top: 0;
		left: 0;
		z-index: 1;
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-align: center;
		-ms-flex-align: center;
		-ms-grid-row-align: center;
		align-items: center;
		-webkit-box-pack: end;
		-ms-flex-pack: end;
		justify-content: flex-end;
		padding-right: 60px;
	}

	@media only screen and (max-width: 575px) {
		.single-timeline-area .timeline-date {
			width: 100px;
		}
	}

	.single-timeline-area .timeline-date::after {
		position: absolute;
		width: 3px;
		height: 100%;
		content: "";
		background-color: #ebebeb;
		top: 0;
		right: 30px;
		z-index: 1;
	}

	.single-timeline-area .timeline-date::before {
		position: absolute;
		width: 11px;
		height: 11px;
		border-radius: 50%;
		background-color: #f1c40f;
		content: "";
		top: 50%;
		right: 26px;
		z-index: 5;
		margin-top: -5.5px;
	}

	.single-timeline-area .timeline-date p {
		margin-bottom: 0;
		color: #020710;
		font-size: 13px;
		text-transform: uppercase;
		font-weight: 500;
	}

	.single-timeline-area .single-timeline-content {
		position: relative;
		z-index: 1;
		padding: 30px 30px 25px;
		border-radius: 6px;
		margin-bottom: 15px;
		margin-top: 15px;
		-webkit-box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
		box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
		border: 1px solid #ebebeb;
	}

	@media only screen and (max-width: 575px) {
		.single-timeline-area .single-timeline-content {
			padding: 20px;
		}
	}

	.single-timeline-area .single-timeline-content .timeline-icon {
		-webkit-transition-duration: 500ms;
		transition-duration: 500ms;
		width: 30px;
		height: 30px;
		background-color: #f1c40f;
		-webkit-box-flex: 0;
		-ms-flex: 0 0 30px;
		flex: 0 0 30px;
		text-align: center;
		max-width: 30px;
		border-radius: 50%;
		margin-right: 15px;
	}

	.single-timeline-area .single-timeline-content .timeline-icon i {
		color: #ffffff;
		line-height: 30px;
	}

	.single-timeline-area .single-timeline-content .timeline-text h6 {
		-webkit-transition-duration: 500ms;
		transition-duration: 500ms;
	}

	.single-timeline-area .single-timeline-content .timeline-text p {
		font-size: 13px;
		margin-bottom: 0;
	}

	.single-timeline-area .single-timeline-content:hover .timeline-icon,
	.single-timeline-area .single-timeline-content:focus .timeline-icon {
		background-color: #020710;
	}

	.single-timeline-area .single-timeline-content:hover .timeline-text h6,
	.single-timeline-area .single-timeline-content:focus .timeline-text h6 {
		color: #3f43fd;
	}
</style>