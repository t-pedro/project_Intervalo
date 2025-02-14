<template>
      <div class="w-11/12 mx-auto">
        <div class="card w-auto">
          <div class="card-header text-lg px-5 py-4 font-bold border-b">
            <div>{{title}}</div>
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
        </div>
      </div>
</template>


<script>
import { useForm } from '@inertiajs/inertia-vue3'

export default {
  props: {
    title: String,
    route: String
  },
  components: {

  },
  setup(props) {
    const form = useForm({
      import_file: null,
    })

    var showSpinner

    function submit() {

      this.showSpinner = true

      form.post(props.route, {
        forceFormData: true,
        onSuccess: () => this.showSpinner = false
      })

    }

    return { form, submit, showSpinner }
  },
  methods: {
  }
}
</script>