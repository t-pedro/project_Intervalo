<template>

<div data-theme="winter"  class="min-h-screen flex flex-col justify-between text-gray-600 ">
  <!-- TOP NAVBAR -->
  <Navheader/>  
  <!-- / TOP NAVBAR -->

  <main class="flex flex-grow items-stretch">
    
    <!-- SIDEBAR     -->
    <Sidebar/> 
    <!-- / SIDEBAR     -->
    <div class="content-wrapper flex flex-col bg-gray-light w-full">
      <slot></slot>
      <Pagefooter/>
    </div>  

  </main>
  
</div>

</template>

<script>
import Navheader  from './Navheader.vue'
import Sidebar    from './Sidebar.vue'
import Pagefooter from './Pagefooter.vue'

export default {
  components: {
    Navheader,
    Sidebar,
    Pagefooter
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
