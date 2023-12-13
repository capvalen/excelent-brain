<template>
  <!-- Modal -->
  <div class="modal fade" id="modalVerDetalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header border-0">
						<h5 class="modal-title" id="exampleModalLabel">Detalles de la consulta</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="text-center mb-2" >
							<img v-if="miniRespuesta.foto!=''" :src="'/img/'+miniRespuesta.foto" alt="Imagen del Profesional" title="Imagen del profesional" class="rounded-circle" width="100px" height="100px" style="object-fit:cover;">
							<img v-else src="/img/doc_default.jpg" alt="Imagen del Profesional" title="Imagen del profesional" class="rounded-circle" width="200px" height="200px" style="object-fit:cover;">
						</div>
						<p><strong>Fecha:</strong> <span class="text-capitalize">{{fechaLectura(miniRespuesta.fecha)}}</span></p>
						<p><strong>Profesional:</strong> <span>{{miniRespuesta.nombre}}</span></p>
						<p><strong>Diagnóstico:</strong> <span>{{miniRespuesta.contenido}}</span></p>
						<div v-if="miniRespuesta.comentarios.length>0">
							<p>Comentarios extras:</p>
							<ul class="list-group mb-2">
								<li v-for="comentario in miniRespuesta.comentarios" class="list-group-item list-group-item-action text-capitalize"><i class="far fa-comment"></i> {{comentario.comment}}</li>
							</ul>
						</div>
						<div class="text-center" v-if="miniRespuesta.firma!=''">
							<img :src="`/storage/${miniRespuesta.firma}`" class="w-75">
						</div>
						<div v-else>
							<p>Sin firma</p>
						</div>
					</div>
				</div>
			</div>
		</div>
</template>

<script>
import moment from 'moment';
export default{
  name: 'modalVerDetalle',
  props: {
    miniRespuesta:[]
  },
  methods:{
    fechaLatam(fecha){
			return moment(fecha).format('DD/MM/YYYY');
		},
		fechaLectura(fecha){
			moment.locale('es')
			return moment(fecha).format('dddd, DD [de] MMMM YYYY [a las] hh:mm a');
		},
  }
}
</script>