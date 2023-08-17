<template>
	<div class="modal fade" id="nuevoAcontecimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo acontencimiento</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<label for="">Edad</label>
				<input type="text" class="form-control">
				<label for="">Acontecimiento</label>
				<input type="text" class="form-control">
				<label for="">Síntomas</label>
				<input type="text" class="form-control">
			</div>
			<div class="modal-footer border-0">
				<button type="button" class="btn btn-outline-success" data-bs-dismiss="modal"><i class="far fa-save"></i> Agregar</button>
			</div>
		</div>
	</div>
</div>
</template>
<script>
export default{
	name: 'nuevoAcontecimiento'
}</script>