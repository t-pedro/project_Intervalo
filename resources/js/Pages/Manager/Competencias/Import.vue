<template>
  <App>
    <!-- CONTENT -->
    <div class="flex-grow flex flex-col">
      <div class="w-11/12 mx-auto flex justify-between items-center">
        <h1 class="my-10 text-4xl font-bold">Importar Competencias</h1>
        <div>
          <a class="btn btn-primary btn-sm mr-2" :href="route('competencia')">Volver</a>
        </div>
      </div>
      <ImportFile :title="'Seleccione archivo | Competencia'" :route="'importfile'"></ImportFile>
      <ImportFile :title="'Seleccione archivo | Competencia Relacionadas'" :route="'importfilerelated'"></ImportFile>
      <ImportError :toast="toast"></ImportError>
    </div>
  </App>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import App from '@/Layouts/App.vue'
import Pageheader from '@/Layouts/Pageheader.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Icons from '@/Layouts/Components/Icons.vue'
import ImportFile from '@/Layouts/Components/ImportFile.vue'
import ImportError from '@/Layouts/Components/ImportError.vue'

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
    Icons,
    ImportFile,
    ImportError
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
        onSuccess: (data) => {
          this.showSpinner = false
        }
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
      this.props.flash = "asd"
    },
  }


}
</script>