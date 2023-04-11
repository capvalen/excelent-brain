<template>
  <div class="col-xl-6 col-md-6 mb-4">
      <div class="card shadow h-100 py-2" :class="`border-left-${this.color}`">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ this.title }}</div>
                      <div class="h5 mb-0">
                        <router-link :to="{path: `./${this.url}`}" class="btn btn-primary btn-sm">Empezar Cuestionario</router-link>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    color: String,
    url: String,
  }
}
</script>