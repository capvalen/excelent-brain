<template>
  <ul class="p-0">
    <!-- <p class="mt-2"> ¿HASTA QUE PUNTO SE HA SENTIDO AFECTADO POR:? Utilice los siguientes criterios:
      <br>
      <span 
      v-for="(value, key, index) in criteria"
      :key="index"
      >
      <b>{{ key }}:</b> {{ value }}
      <br>
      </span>
    </p> -->

    <li v-for="item in items" :key="item.id">
        {{item.id}}. {{item.content}}

        <br>
        <div v-if="!item.especial">
        <ExamInput 
        v-for="(el, key, index) in item.answer || criteria"
        :key="index"
        :id="item.id"
        :criteria="el"
        :value="valuesDefault ? valuesDefault[index] : index"
        @inputValue="inputValue"
        />
        </div>
        <div v-else>
          <ExamInput 
          v-for="(el, key, index) in criteriab"
          :key="index"
          :id="item.id"
          :criteria="el"
          :value="valuesDefault ? valuesDefault[index] : index"
          @inputValue="inputValue"
          />
        </div>
        
        <!-- <ExamInput 
        v-else  
        v-for="(el, key, index) in criteriab"
        :id="item.id"
        :key="'b'+index"  
        :criteria="el"
        :value="valuesDefault ? valuesDefault[index] : index"
        @inputValue="inputValue"
        /> -->
    </li>

    <button class="btn btn-success" @click="print">Imprimir Respuestas</button>
  </ul>
</template>

<script>
import ExamInput from './ExamInput.vue';

export default {
  components: {ExamInput},

  data() {
    return {
      objs: [],
      form: {}
    }
  },
  
  props: {
    items: Array,
    criteria: Object,
    criteriab: Object,
    valuesDefault: Array,
  },

  methods: {
    inputValue({nro, value, name}){
      const itemSelect = this.items[parseInt(nro) - 1];      
      itemSelect.value = value

      this.form = {
        ...this.form,
        [name]: itemSelect
      }

      var name = 'inlineRadioOptions'+nro
      var obj = {nro, value}

      if(this.verifDuplicated(obj)){
        this.objs.push({...obj})
      }

      this.$emit('senddata', this.objs)
    },

    verifDuplicated(obj2){
      if(this.objs.filter(obj => obj.nro == obj2.nro).length == 0){
        console.log('no coincidencia de nro')
        return true
      }else{
        console.log('coincidencia de nro')
        this.objs = this.objs.filter(obj => obj.nro != obj2.nro)
        this.objs.push({...obj2})
        return false
      }
    },

    print() {
      window.print()
    },
  }
}
</script>

<style scoped>
  ul{
    list-style: none;
  }

  li{
    background-color: #fff;
    font-size: 20px;
    border: 1px solid rgba(0, 0, 0, 0.493);
    margin: 10px 0px 10px 0px;
    border-radius: 5px;
    padding: 10px;
  }

  .input-name {
    width: 70%;
    flex: 1 0 auto;
    min-width: 250px;
  }

  .input-select {
    width: 25%;
    flex: 1 0 auto;
  }

  .scrollable{
    background-color: #fff;
    color: #000;
    position: fixed;
    right: 0;
    border: 1px solid rgba(0, 0, 0, 0.493);
    z-index:99;
    border-radius: 5px;
    padding: 5px;
  }

  .gap {
      gap: 15px;
  }

  @media screen and (max-width: 750px) {
    .scrollable {
        position: relative;
    }
  }
</style>