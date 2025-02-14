<template>
  <div
    class="card bg-base-100 shadow-xl transition duration-300 ease-in"
    :class="{
      'bg-blue-50': this.selected == true,
      'col-span-2': this.expand == true,
      'col-span-1': this.expand == false,
    }"
  >
    <div class="card-body ">
      <h2 class="card-title text-academy-accent h-12">
        {{ item.competencia }}
      </h2>
      <p v-if="item.definicion" class="h-24 w-auto text-justify ">{{ item.definicion.slice(0, 150) }}...</p>
      <p v-else class="h-24 text-justify">...</p>
      <div class="flex items-center justify-between ">
        <button @click="sendItem()">
          <span class="font-bold hover:underline duration-100">Leer mas</span>
        </button>

        <div class="card-actions justify-end ">
          <!-- <a @click="this.expand = !this.expand"> Más</a>
                <a @click="this.expand = !this.expand"> Menos</a> -->
          <button
            v-show="!this.selected"
            @click="this.selected = !this.selected"
            class="btn btn-primary space-x-2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              class="h-6 w-6 stroke-current"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
            <span>Seleccionar</span>
          </button>
          <button
            v-show="this.selected"
            @click="this.selected = !this.selected"
            class="btn space-x-2"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span>Deseleccionar</span>
          </button>
        </div>
      </div>
      <!-- <div v-show="this.expand">
                <div>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quas laudantium perferendis officiis non sed nesciunt unde exercitationem suscipit sunt? Cum sed voluptatum fugiat illo incidunt! Quaerat vel dolor debitis facere.
                    Labore vel harum accusamus nemo ducimus quam consequuntur eos! Officiis consequuntur incidunt voluptatum maxime, aliquid fugit quas reprehenderit, magnam, neque cumque quis! Reiciendis in ab similique dolore. Tempore, saepe veniam?
                    Eum, repellendus. Placeat blanditiis laborum aliquam aspernatur magni repudiandae pariatur esse rerum porro? Ipsum illum laboriosam optio! Libero error ut aliquam ex consequuntur facilis! Necessitatibus similique voluptates suscipit ullam maxime?
                    Ad quae hic cum quo aspernatur corporis libero tenetur esse impedit exercitationem accusantium reiciendis enim, vel quam adipisci necessitatibus repellat accusamus aliquid saepe error voluptate rerum illo? Libero, in ipsam!
                    Commodi, quidem omnis recusandae nam ipsam odit architecto sapiente iusto alias, eligendi et sint minima! Fugiat, dolorum sit. Distinctio adipisci deserunt iste magni eos necessitatibus modi natus eaque in! Recusandae.
                </div>
            </div> -->
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object,
    index: [String, Number],
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

<style>
</style>