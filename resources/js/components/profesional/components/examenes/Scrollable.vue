<template>
   <div class="scrollable">
      <div class="scrollable__content">
        <div 
        v-for="(value, key, index) in this.values"
        :key="index"
        >
        <span v-if="key != 'created_at'">{{ key }} - {{ value }}</span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-3" @click="$emit('sumatoria', true)">Calcular y guardar</button>
  </div>
</template>

<script>
export default {
  name: 'Scrollable',

  props: {
    values: Object
  },

  methods: {
    sumatoria() {
      console.log(this.values)
    }
  }
}
</script>

<style scoped>
  .scrollable {
    max-width: 500px;
    background-color: #fff;
    color: #000;
    position: fixed;
    right: 0;
    border: 1px solid rgba(0, 0, 0, 0.493);
    z-index:99;
    border-radius: 5px;
    padding: 5px;
    opacity: .5;
  }

  .scrollable:hover {
    opacity: 1;
  }

  .scrollable__content {
    max-height: 250px;
    overflow: auto;
  }

   @media screen and (max-width: 750px) {
    .scrollable {
      opacity: 1;
      position: relative;
    }
  }
</style>