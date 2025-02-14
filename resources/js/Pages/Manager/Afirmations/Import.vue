<template>
  <App>
    <!-- CONTENT -->
    <div class="flex-grow flex flex-col">
      <div class="w-11/12 mx-auto flex justify-between items-center">
        <h1 class="my-10 text-4xl font-bold">Importar Afirmacion</h1>
        <div>
          <a class="btn btn-primary btn-sm mr-2" :href="route('afirmation')">Volver</a>
        </div>
      </div>
      <div class="w-11/12 mx-auto">
        <div class="card w-auto">
          <div class="card-header text-lg px-5 py-4 font-bold border-b">
            <div>Seleccionar Archivo</div>
          </div>
          <div class="pt-6 pb-8 px-6">
            <form @submit.prevent="submit">
              <input type="file" @input="form.import_file = $event.target.files[0]" ref="import_file" />
              <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
              </progress>
              <button
                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                type="submit">Importar</button>
            </form>
          </div>
          <div v-if="toast && toast.status == 200" class="my-5 bg-green-100 rounded-md p-3 flex ">
            <svg class="stroke-2 stroke-current text-green-600 h-8 w-8 mr-2 flex-shrink-0" viewBox="0 0 24 24"
              fill="none" strokeLinecap="round" strokeLinejoin="round">
              <path d="M0 0h24v24H0z" stroke="none" />
              <circle cx="12" cy="12" r="9" />
              <path d="M9 12l2 2 4-4" />
            </svg>
            <div class="text-green-700">
              <div class="font-bold text-xl">Proceso finalizado!</div>

              <div v-html="toast.message"></div>
            </div>
          </div>

          <div v-if="toast && toast.status != 200" class="my-5 bg-red-100 rounded-md p-3 flex ">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 tex text-red-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-red-700">
              <div class="font-bold text-xl">Error!</div>

              <div v-html="toast.message"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </App>
</template>


<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import App from '@/Layouts/App.vue'
import Pageheader from '@/Layouts/Pageheader.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Icons from '@/Layouts/Components/Icons.vue'

export default {
  props: {
    section: String,
    title: String,
    file: String,
    flash: Object,
    imports: Object,
    toast: Object
  },
  components: {
    Head,
    App,
    Pageheader,
    Link,
    Icons
  },
  setup() {
    const form = useForm({
      import_file: null,
    })

    var showSpinner

    function submit() {

      this.showSpinner = true

      form.post('importfile', {
        forceFormData: true,
        onSuccess: () => this.showSpinner = false
      })

    }

    return { form, submit, showSpinner }
  },

  // data(){
  //     return{
  //       persons:"",
  //       showImportId:""
  //     }
  // },

  methods: {
    closeResponse() {
      this.props.flash = ""
    },
  }


}
</script>