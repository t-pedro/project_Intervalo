<template>
      <div class="page-title-wrapper bg-white border-b">
        <!-- {{buttons}} -->
        <div class="page-title items-center px-5">
          <div class="flex justify-between">
            <div class="">
                <div class="title flex items-center font-bold text-lg text-gray-900 py-8">                
                  <a href="#">
                    <svg class="w-5 h-5 mr-2"
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                  </a>
                  {{section}}<span class="font-normal ml-1 "> - {{title}}</span>
                </div>
            </div>
            <!-- <div class="header-elements flex-row self-center"> -->
            <div class="flex flex-row">              
              <slot name="buttons"></slot>
            </div>
          </div>
        </div>

        <div class="page-title-breadcrum border-t flex justify-between px-5 bg-gray-lightfa text-black-333 text-tiny">
          <div class="flex items-center">
            <!-- <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <div class="ml-2">  Home / <span class="text-gray-400 ">Dashboard</span></div> -->
          </div>

          <div class="breadcrumb-elements">
            <div class="flex">
              <!-- <a href="#" class="flex items-center py-3">
                <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Support</a>
              <a href="#" class="flex items-center ml-5 py-3">
                <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Contact</a> -->
            </div>
          </div>
          
        </div>
      </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    props: {
        section:String,
        title: String,
        // personas: Object,
        // buttons: Object,
    },
    name: 'Pageheader',
    components: {
      Link,
    }
}
</script>