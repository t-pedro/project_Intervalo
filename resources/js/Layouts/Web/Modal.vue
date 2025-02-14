<template>
  <div class="fixed z-10 h-screen w-screen flex justify-center items-center overflow-y-auto" @click="item.visible = false">
    <div class="card bg-base-100 w-3/5 shadow-xl transition duration-300 ease-in">
    
      <div class="px-6 pt-8 pb-4">
        <div class="font-bold text-xl mb-2 text-academy-accent text-center">{{ item.competencia }}</div>
      </div>
    
      <div class="px-6 pt-4 pb-2">
        <p class="text-justify">{{ item.definicion }}</p>
        <br>

        <p class="text-justify"><b>Comportamientos observables: </b>{{ item.comportamiento }}</p>
      </div>
    
      <div class="card-body">
        <div class="flex justify-center">
          <div class="card-actions justify-end">
            <button @click="item.visible = false" class="btn btn-secondary space-x-2 mt-4">
              <span>Ocultar</span>
            </button>
          </div>
        </div>
      </div>
    
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object,
  },
  data() {
    return {
      selected: false,
      expand: false,
    };
  },
  methods: {
    sendItem() {
      this.item.visible = true;
      this.$emit("sendItem", this.item);
    },
  },
};
</script>
