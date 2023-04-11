<template>
  <div class="modal fade" id="pagoExtras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pago Extras</h5>
          <button type="button" id="cerrModal" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form @submit.prevent>
            <div class="form-group row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="customer">Cliente</label>
                  <input type="text" name="customer" required id="customer" class="form-control"
                    v-model="form.customer">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="price">Precio</label>
                  <input type="number" name="price" required id="price" class="form-control" v-model="form.price">
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label for="type">Tipo de pago</label>
                <select class="form-control" id="type" required name="type" v-model="form.type">
                  <option value="0">Certificado</option>
                  <option value="1">Paquete de menbresia</option>
                  <option value="2">Paquete kurame</option>
                  <option value="3">Informe</option>
                  <option value="4">Otros</option>
                </select>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label for="date">Fecha</label>
                  <input type="date" class="form-control" required name="date" id="date" v-model="form.date">
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="form-group">
                <label for="name">Observacion</label>
                <textarea name="observation" class="form-control" id="observation" rows="5"
                  v-model="form.observation"></textarea>
              </div>
            </div>

            <div>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button @click="saveData" type="submit" class="btn btn-primary" data-dismiss="modal">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PagosExtras',

  data() {
    return {
      form: {
        customer: null,
        price: null,
        type: null,
        observation: null,
        date: null
      }
    }
  },

  methods: {
    async saveData() {
      if (!this.form.customer || !this.form.price || !this.form.type || !this.form.date)
        return this.$swal("Datos incompletos")

      this.$swal("Guardando datos")

      this.axios.post('/api/paymentExtra', this.form)
        .then(res => {
          for (let value in this.form) {
            this.form[value] = ''
          }

          this.$swal(res.data)
        })
        .catch(err => {
          this.$swal('Hubo un error')
        })
    }
  }
}
</script>