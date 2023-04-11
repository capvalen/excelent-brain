<template>
  <div>
    <div class="form-check form-check-inline" @click="inputValue">
      <input class="form-check-input" type="radio" :name="`inlineRadioOptions${this.id}`" :id="`inlineRadio${this.id}-${this.criteria}`" :value="this.value">
      <label class="form-check-label" :for="`inlineRadio${this.id}-${this.criteria}`">{{this.criteria}}</label>
    </div>
    <br>
  </div>
</template>

<script>
export default {
  props: {
    id: String,
    criteria: String,
    value: [Number, String]
  },
  
  methods: {
    inputValue() {
      if (event.target.matches('.form-check-input')) {
        let name = event.target.name
        this.$emit('inputValue', {nro: this.id, value: this.value, name})
      }
    }
  }
}
</script>