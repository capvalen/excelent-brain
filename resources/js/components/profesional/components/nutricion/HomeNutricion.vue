<template>
	<div>
		<h3>Nutrición</h3>
		<button class="btn btn-outline-primary" data-bs-target="#nuevoHistorialNutricion" data-bs-toggle="modal"><i class="far fa-comment-alt"></i> Iniciar Historial Nutricional</button>


		<!-- Modal de examenes -->
		<div class="modal fade" id="nuevoHistorialNutricion" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header ">
						<h5 class="modal-title" id="infoModalLabel">Exámenes</h5>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
					</div>
					<div class="modal-body">
						<p class="mt-2"><strong>1. Antecedentes:</strong></p>
						<div class="row">
							<div class="col">
								<label for="">Peso</label>
								<input type="number" class="form-control" v-model="nutricion.peso">
							</div>
							<div class="col">
								<label for="">Talla</label>
								<input type="number" class="form-control" v-model="nutricion.talla">
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label for="">IMC</label>
								<input type="number" class="form-control" v-model="nutricion.imc">
							</div>
							<div class="col">
								<label for="">Perímetro pabdominal</label>
								<input type="number" class="form-control" v-model="nutricion.perimetro">
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label for="">% de grasa corporal</label>
								<input type="number" class="form-control" v-model="nutricion.grasa">
							</div>
						</div>

						<p class="mt-2"><strong>2. Hábitos almimenticios:</strong></p>
						<div class="row">
							<div class="col">
								<label for="">N° de comidas</label>
								<input type="number" class="form-control" v-model="nutricion.comidas">
							</div>
							<div class="col">
								<label for="">Intolerancia</label>
								<select class="form-select" id="" v-model="nutricion.intolerancia">
									<option value="0">No</option>
									<option value="1">Si</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label for="">Alergias</label>
								<select class="form-select" id="" v-model="nutricion.alergias">
									<option value="0">No</option>
									<option value="1">Si</option>
								</select>
							</div>
							<div class="col">
								<label for="">Fuma</label>
								<select class="form-select" id="" v-model="nutricion.fuma">
									<option value="0">No</option>
									<option value="1">Si</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<label for="">Bebe</label>
								<select class="form-select" id="" v-model="nutricion.bebe">
									<option value="0">No</option>
									<option value="1">Si</option>
								</select>
							</div>
						</div>
						<p class="mt-2"><strong>3. Signos y síntomas:</strong></p>
						<textarea rows="3" class="form-control" v-model="nutricion.signos"></textarea >
						<p class="mt-2"><strong>4. Diagnóstico nutricional:</strong></p>
						<textarea rows="3" class="form-control" v-model="nutricion.diagnostico"></textarea>
						<p class="mt-2"><strong>5. Plan nutricional:</strong></p>
						<label for="">Dieta</label>
						<textarea rows="3" class="form-control" v-model="nutricion.dieta"></textarea>
						<label for="">Suplemento o complemento nutricional</label>
						<textarea rows="3" class="form-control" v-model="nutricion.suplemento"></textarea>

						<button class="btn btn-outline-primary mt-2" @click="guardar()" data-bs-dismiss="modal"><i class="far fa-save"></i> Crear plan inicial</button>


					</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
export default{
	name: 'Nutricion',
	props:['dataCies'],
	data(){ return {
		nutricion:{
			idPaciente:null, peso:0, talla:0, imc:0, perimetro:0, grasa:0, comidas:1, intolerancia:0, alergias:0, fuma:0, bebe:0, signos:'', diagnostico:'', dieta:'', suplemento:''
		}
	}},
	methods: {
		guardar(){
			this.nutricion.idPaciente = this.$route.params.idPaciente
			this.axios.post(`/api/crearNutricionPrimera`, this.nutricion)
			.then(resp => console.log(resp.data) )
		}
	},
}
</script>