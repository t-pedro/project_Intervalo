<template>
  <div class="w-11/12 mx-auto">
    <div class="card w-auto">
      <div v-if="toast && toast.status == 200" class="my-5 bg-green-100 rounded-md p-3 flex ">
        <svg class="stroke-2 stroke-current text-green-600 h-8 w-8 mr-2 flex-shrink-0" viewBox="0 0 24 24" fill="none"
          strokeLinecap="round" strokeLinejoin="round">
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
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-red-700">
          <div class="font-bold text-xl">Error!</div>

          <div v-html="toast.message"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    toast: Object,
  },
  components: {
  }
}
</script>