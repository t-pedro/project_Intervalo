<template>
  <div class="card bg-base-100 shadow-xl transition duration-300 ease-in" :class="{
    'bg-blue-50': this.selected == true,
    'col-span-2': this.expand == true,
    'col-span-1': this.expand == false,
  }">
    <div class="card-body ">
      <div class="flex items-center justify-between ">
        <h2 class="card-title text-academy-accent h-12">
          {{ item.name }}
        </h2>
        <div v-if="item.diagnostico_360">
          <Icons v-if="item.diagnostico_360" name="globe-alt" class="h-8 w-8 text-gray-800 mr-2 cursor-pointer" title="Diagnóstico 360"/>
        </div>
      </div>
      <p v-if="!item.name" class="h-24 w-auto text-justify ">{{ item.name.slice(0, 150) }}...</p>
      <p v-else class="h-24 text-justify">Un diagnóstico empresarial es una evaluación de la gestión, operatividad y
        funcionamiento de una empresa que tiene como objetivo detectar sus fortalezas y debilidades para generar
        estrategias que eleven su bienestar y desempeño.</p>
      <div class="flex">
        <span v-for="c in item.competencias"
          class="mr-2 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
          <Icons name="badge-check" class="h-5 w-5 mr-2" />{{ c.competencia }}
        </span>
      </div>
      <div class="flex items-center justify-between ">
        <button>
          <span class="font-bold hover:underline duration-100"></span>
        </button>

        <div class="card-actions justify-end ">
          <button @click="send(item)" class="btn btn-primary space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 stroke-current">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Iniciar Diagnóstico</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Icons from "@/Layouts/Components/Icons.vue";
export default {

  props: {
    item: Object,
    index: [String, Number]
  },
  components: {
    Icons
  },
  data() {
    return {
      expand: false
    };
  },
  methods: {
    send(item) {
      this.$emit("sendDiagnostico", item);
    },
  }
};
</script>
