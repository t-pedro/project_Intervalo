<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'nav-link flex py-3 px-5 items-center bg-blue-500 hover:bg-dark-light m-1 mt-1 transition duration-500 ease-out'
                    : 'nav-link flex py-3 px-5 items-center hover:bg-dark-light  m-1 mt-1 transition duration-500 ease-out'
            }
        }
    }
</script>