<template>

    <div data-theme="winter" class="min-h-screen flex flex-col justify-between text-gray-600 ">
        <Navheader/>  
        <main class="flex flex-grow items-stretch">
            <div class="content-wrapper flex flex-col bg-gray-50  w-full">
                <slot></slot>
            </div>  
        </main>
        <Footer/>  
    </div>

</template>

<script>
import Navheader  from './Navheader.vue'
import Footer from './Footer.vue'

export default {
  components: {
    Navheader,
    Footer
  },

  name: 'App',
  
  props: {
    msg: String
  },

  data: () => ({
  }),

  methods: {
  }
}
</script>
