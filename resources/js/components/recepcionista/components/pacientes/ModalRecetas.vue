<template>
  <div class="modal fade" id="recetasModal" tabindex="-1" aria-labelledby="modalEvolution" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="infoModalLabel">Ver recetas</h5>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
          </div>

          <div class="modal-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha</th>
                  <th>Ver Receta</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="dataPatient" v-for="(prescription, index) in dataPatient.prescriptions" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ prescription ? prescription.attention_date : '...' }}</td>
                  <td>
                    <a v-if="prescription" class="btn btn-success" :href="`/api/pdf/${prescription.id}`" target="_blank"><i class="fa-solid fa-file-pdf"></i> Ver PDF</a>
                  </td>
                </tr>
								<tr v-if="dataPatient.prescriptions.length==0">
									<td colspan="4">No hay recetas</td>
								</tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
  export default {
    name: 'Recetas',
    props: {
      dataPatient: Object
    }
  }
</script>