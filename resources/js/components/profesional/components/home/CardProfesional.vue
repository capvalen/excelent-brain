<template>
<div class="col-xl-4 col-lg-5">
  <div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div
      class="
        card-header
        bg-warning
        py-3
        d-flex
        flex-row
        align-items-center
        justify-content-between
      "
    >
      <h6 class="m-0 font-weight-bold text-white">Datos Personales</h6>
      <div class="dropdown no-arrow">
          <a
              class="dropdown-toggle"
              href="#"
              role="button"
              id="dropdownMenuLink"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
          >
              <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
              <div class="dropdown-header">Opciones:</div>
              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Editar Datos</a>
          </div>
      </div>
    </div>
      <!-- Card Body -->
  <div class="card-body text-center">
      <img v-if="prof.photo" :src="'/storage/'+prof.photo" alt="Imagen del Profesional" title="Imagen del profesional" class="rounded-circle" width="100px" height="100px" style="object-fit:cover;">
      <img v-else src="/storage/logo/logo.svg" alt="Profesional" class="rounded-circle" width="100px" height="100px" style="object-fit:cover;">
      <div class="profesional-data mt-3">
          <p>Nombre: {{professional.name}} {{professional.lastname}}</p>
          <p>Profesión: {{professional.profession}}</p>
          <p>Telefono: {{professional.phone}}</p>
      </div>
  </div>
  </div>

</div>
</template>

<script>
import EditModal from './EditModal.vue'

export default {
  name: "card-prof",

  data() {
    return {
      professional:this.prof
    }
  },
  props:{
      prof: Object
  }
};
</script>
