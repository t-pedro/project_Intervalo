<template>
    <header class="bg-dark-light text-white border-gray-200 flex items-center justify-between px-5 pr-10 text-base">
        <div class="navbar-brand text-3xl py-5 font-bold leading-4 w-96"><span>onMedia</span></div>
        <!-- <div class="navbar flex flex-grow justify-between items-center">
            <ul class="navbar-bar flex text-tiny items-center">
            <li class="navbar-item">
                <a @click.prevent="showSidebar = !showSidebar" href="" class="navbar-link">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                </a>
            </li>
            </ul>
            <ul class="navbar-bar flex text-tiny items-center"> 
           </ul>
        </div> -->
            <div class="flex flex-col items-end mr-3">
                <span class="text-white">Hola {{ $page.props.user.name }}</span>
                <span class="text-white text-sm ">{{ $page.props.userRol.name }} </span>

            </div>

    </header>
</template>

<script>

// import JetDropdown from '@/Jetstream/Dropdown.vue'
// import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
import Userdropdown from '@/Layouts/Navbar/Userdropdown.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

export default {
    name: 'Navheader',

    components: {
        Head,
        Userdropdown,
        // JetDropdown,
        // JetDropdownLink,
        Link,
    },
    methods:{
        logout() {
            this.$inertia.post(route('logout'));
        },
    }


}
</script>