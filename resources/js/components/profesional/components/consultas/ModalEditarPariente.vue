<template>
  <div class="modal fade" id="modalEditarPariente" tabindex="-1" aria-labelledby="modalEditarParienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalEditarParienteLabel">Contacto de parientes</h5>
          <button type="button" id="closeModal" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="guardarCambios">
            <div class="row">
              <!-- Utilizamos v-for para evitar duplicación de código -->
              <div v-for="(index, i) in 2" :key="i" class="col col-md-6" :class="{'border-left': i === 1}">
                <label><strong>Pariente N° {{ i + 1 }}</strong></label>
                
                <div class="mb-3">
                  <label :for="'nombre-pariente-'+i" class="form-label">Nombre</label>
                  <input 
                    type="text" 
                    :id="'nombre-pariente-' + i" 
                    class="form-control text-capitalize" 
                    v-model="parientes[i].nombre"
                    :placeholder="'Nombre del pariente ' + (i + 1)"
                    required
                  >
                </div>
                
                <div class="mb-3">
                  <label :for="'celular-pariente-'+i" class="form-label">Celular</label>
                  <input 
                    type="tel" 
                    :id="'celular-pariente-' + i" 
                    class="form-control" 
                    v-model="parientes[i].celular"
                    pattern="[0-9]+"
                    :placeholder="'Celular del pariente ' + (i + 1)"
                    required
                  >
                </div>
                <div class="mb-3">
                  <label :for="'celular-pariente-'+i" class="form-label">Parentesco</label>
                  <input 
                    type="parentesco" 
                    :id="'parentesco-pariente-' + i" 
                    class="form-control" 
                    v-model="parientes[i].parentesco"
                    :placeholder="'Parentesco con el paciente ' + (i + 1)">
                </div>
                <div class="mb-3 d-none">
                  <label :for="'parentesco-pariente-'+i" class="form-label">Parentesco</label>
                  <select 
                    :id="'parentesco-pariente-' + i" 
                    class="form-select text-capitalize" 
                    v-model="parientes[i].parentesco"
                    required
                  >
                    <option value="" disabled selected>Seleccione parentesco</option>
                    <option v-for="tipo in tiposParentesco" :key="tipo" :value="tipo">{{ tipo }}</option>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button 
            class="btn btn-primary" 
            @click="guardarCambios"
            :disabled="cargando"
          >
            <i class="fa-solid" :class="cargando ? 'fa-spinner fa-spin' : 'fa-floppy-disk'"></i> 
            {{ cargando ? 'Guardando...' : 'Actualizar contactos' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalEditarPariente',
  props: {
    // Mejoramos la definición de props con validación
    relative: {
      type: Array,
      required: true
    },
    id: {
      type: [Number, String],
      required: true
    }
  },
  emits: ['updatePariente', 'error'],
  data() {
    return {
      // Estructura de datos mejorada
      parientes: [
        { nombre: '', celular: '', parentesco: '' },
        { nombre: '', celular: '', parentesco: '' }
      ],
      tiposParentesco: ['Padre', 'Madre', 'Hermano/a', 'Tío/a', 'Abuelo/a', 'Otro'],
      cargando: false,
      errores: {}
    }
  },
  created() {
    // Inicializamos los datos cuando el componente se crea
    this.cargarDatosParientes();
  },
  watch: {
    // Observamos cambios en los props para actualizar datos
    relative: {
      handler() {
        this.cargarDatosParientes();
      },
      deep: true
    }
  },
  methods: {
    cargarDatosParientes() {
      // Método separado para inicializar datos
      for (let i = 0; i < 2; i++) {
        if (this.relative[i]) {
          this.parientes[i] = {
            nombre: this.relative[i].name || '',
            celular: this.relative[i].phone || '',
            parentesco: this.relative[i].kinship || ''
          };
        } else {
          this.parientes[i] = { nombre: '', celular: '', parentesco: '' };
        }
      }
    },
    validarDatos() {
      // Validamos los datos antes de enviar
      this.errores = {};
      let isValid = true;
      
      // Solo validamos el primer pariente como obligatorio
      if (!this.parientes[0].nombre) {
        this.errores.nombre0 = 'El nombre del primer pariente es obligatorio';
        isValid = false;
      }
      
      if (!this.parientes[0].celular) {
        this.errores.celular0 = 'El celular del primer pariente es obligatorio';
        isValid = false;
      } else if (!/^\d+$/.test(this.parientes[0].celular)) {
        this.errores.celular0 = 'El celular debe contener solo números';
        isValid = false;
      }
      
      if (!this.parientes[0].parentesco) {
        this.errores.parentesco0 = 'El parentesco del primer pariente es obligatorio';
        isValid = false;
      }
      
      // Para el segundo pariente, validamos solo si hay algún campo lleno
      const segundoParienteTieneAlgunDato = 
        this.parientes[1].nombre || 
        this.parientes[1].celular || 
        this.parientes[1].parentesco;
        
      if (segundoParienteTieneAlgunDato) {
        if (!this.parientes[1].nombre) {
          this.errores.nombre1 = 'El nombre es obligatorio si completa otros datos';
          isValid = false;
        }
        
        if (!this.parientes[1].celular) {
          this.errores.celular1 = 'El celular es obligatorio si completa otros datos';
          isValid = false;
        } else if (!/^\d+$/.test(this.parientes[1].celular)) {
          this.errores.celular1 = 'El celular debe contener solo números';
          isValid = false;
        }
        
        if (!this.parientes[1].parentesco) {
          this.errores.parentesco1 = 'El parentesco es obligatorio si completa otros datos';
          isValid = false;
        }
      }
      
      return isValid;
    },
    async guardarCambios() {
      // Validamos antes de guardar
      if (!this.validarDatos()) {
        this.$emit('error', 'Por favor, corrija los errores en el formulario');
        return;
      }
      
      this.cargando = true;
      
      try {
        // Preparamos los datos en el formato que espera la API
        const datosParaEnviar = {
          nombre: this.parientes[0].nombre,
          celular: this.parientes[0].celular,
          parentesco: this.parientes[0].parentesco,
          nombre2: this.parientes[1].nombre,
          celular2: this.parientes[1].celular,
          parentesco2: this.parientes[1].parentesco
        };
        
        const response = await this.axios.post(`/api/editarPariente/${this.id}`, datosParaEnviar);
        
        if (response.data.msg) {
          // Convertimos nuestro formato de datos al formato que espera el componente padre
          const parientesActualizados = [
            {
              name: this.parientes[0].nombre,
              phone: this.parientes[0].celular,
              kinship: this.parientes[0].parentesco
            }
          ];
          
          if (this.parientes[1].nombre) {
            parientesActualizados.push({
              name: this.parientes[1].nombre,
              phone: this.parientes[1].celular,
              kinship: this.parientes[1].parentesco
            });
          }
          
          this.$emit("updatePariente", parientesActualizados);
          
          // Cerramos el modal
          document.querySelector("#modalEditarPariente #closeModal").click();
        } else {
          this.$emit('error', 'Error al guardar los datos');
        }
      } catch (error) {
        console.error('Error al actualizar parientes:', error);
        this.$emit('error', 'Error al conectar con el servidor');
      } finally {
        this.cargando = false;
      }
    }
  }
}
</script>

<style scoped>
.border-left {
  border-left: 1px solid #dee2e6;
}

/* Estilos para campos con error */
.campo-error {
  border-color: #dc3545;
}

.texto-error {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}
</style>