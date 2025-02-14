<template>
    <div class="flex">
        <div class="w-1/2">
            {{item.text}}
        </div>
        <!--   -->
        <div class="w-1/2 flex justify-evenly items-center">
            <input type="radio" :name="item.id" class="appearance-none" v-model="item.value" value="1">
            <input type="radio" :name="item.id" class="appearance-none" v-model="item.value" value="2">
            <input type="radio" :name="item.id" class="appearance-none" v-model="item.value" value="3">
            <input type="radio" :name="item.id" class="appearance-none" v-model="item.value" value="4">
            <input type="radio" :name="item.id" class="appearance-none" v-model="item.value" value="5">
        </div>
    </div>
     <!-- <div class="card w-96 bg-base-100 shadow-xl mx-auto col-span-1"
         :class="{'bg-blue-50' : this.selected == true }" >
        <div class="card-body">
            <h2 class="card-title text-academy-accent">{{item.competencia}}</h2>
            <p>{{item.resume}}</p>
            <div class="card-actions justify-end">
                <button v-show="!this.selected" @click="this.selected = !this.selected" class="btn btn-primary space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Seleccionar</span>
                </button>
                <button v-show="this.selected" @click="this.selected = !this.selected" class="btn space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>                    
                    <span>Deseleccionar</span>
                </button>
            </div>
        </div>
    </div> -->
</template>

<script>
    export default {
        props:{
            item: Object,
            index: String
        },
        data(){
            return{
                selected: false
            }
        }
    }
</script>

