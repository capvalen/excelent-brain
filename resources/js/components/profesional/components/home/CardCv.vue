<template>
    <div class="col-xl-8 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header bg-warning py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Resumen del Profesional</h6>
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
                    <div
                        class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink"
                    >
                        <div class="dropdown-header">Opciones:</div>
                        <a
                        class="dropdown-item"
                        href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        >Editar Datos</a
                        >

                    </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="profesional-cv">
                        <p style="white-space: pre-line">{{professional.cv_description}}</p>
                    </div>
                </div>
            </div>
        </div>
</template>
<script>
export default {
    data() {
       return{
           professional:this.prof
       }
    },
    props:{
        prof:Object
    }
}
</script>
