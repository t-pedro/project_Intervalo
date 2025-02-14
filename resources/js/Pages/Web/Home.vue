<template>
  <!--eslint-disable -->
  <transition name="expand">
    <Modal class="bg-gray-900/50" :item="modalContent" v-if="modalContent && modalContent.visible" />
  </transition>
  <App>
    <div class="hero" style="background-image: url('img/banner-aprendizaje-por-competencias.jpeg');">
      <div class="hero-overlay bg-opacity-60"></div>
      <div class="hero-content text-center text-neutral-content">
        <div class="w-4/5 py-20 flex flex-col items-center justify-center">
          <h1 class="mb-5 text-4xl lg:text-5xl font-bold">
            Diagnostico de Competencias
          </h1>
        </div>
      </div>
    </div>
    <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
    <div class="content w-full">

      <div v-if="start" class="flex flex-wrap justify-around">
        <DiagnosticoItem @sendDiagnostico="sendDiagnostico" v-for="(d, idx) in diagnosticos"
          class="lg:w-[97%] md:w-[97%] sm:w-[100%] my-5 h-80" :key="d.id" :item="d" :index="idx" />
          <hr>
      </div>

      <div v-if="show_competencias">
        <div v-if="start" class="flex justify-center mt-4">
          <div class="flex justify-end">
            <a @click="registerUser($page.props.user)" class="btn btn-primary space-x-2">
              <span>Comenzar</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      
        <div v-if="start" class="flex flex-wrap justify-around">
          <CompetenciaItem @sendItem="sendItem" v-for="(c, idx) in competencies"
          class="lg:w-[30%] md:w-[48%] sm:w-[100%] my-5 h-80" :key="c.id" :item="c" :index="idx"
          @click="selectCompetencia(c.id)" />
        </div>
      </div>
      
      <RegisterItem v-if="register" 
                    @register="storePerson"
                    @return_start="register = !register, start = !start, this.compselected = []">
      </RegisterItem>

      <Register360Item v-if="register360" 
                       :diagnostico="this.diagnostico" 
                       :users="users" 
                       @storeDiagnostico360="storeDiagnostico">
      </Register360Item>

      <QuizItem v-if="quiz" :afirmations="afirmations" @processQuiz="processQuiz"
        @return_start="(quiz = !quiz, start = !start, this.compselected = [])"></QuizItem>

      <ResultItem v-if="result" :resultado="resultado"
        @return_start="result = !result, start = !start, this.compselected = []"></ResultItem>
    </div>
  </App>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import App from "@/Layouts/Web/App.vue";
import Icons from "@/Layouts/Components/Icons.vue";
import CompetenciaItem from "@/Layouts/Web/CompetenciaItem.vue";
import DiagnosticoItem from "@/Layouts/Web/DiagnosticoItem.vue";
import Modal from "@/Layouts/Web/Modal.vue";
import RegisterItem from '@/Pages/Web/Register.vue';
import Register360Item from '@/Pages/Web/Register360.vue';
import QuizItem from '@/Pages/Web/Quiz.vue';
import ResultItem from '@/Pages/Web/Result.vue';
import Toast from '@/Layouts/Components/Toast.vue'

export default {
  props: {
    competencias: Object,
    diagnosticos: Object,
    users: Object,
    show_competencias: Boolean
  },
  components: {
    Head,
    App,
    Icons,
    CompetenciaItem,
    DiagnosticoItem,
    Modal,
    RegisterItem,
    Register360Item,
    QuizItem,
    ResultItem,
    Toast,
  },
  data() {
    return {
      modalContent: null,
      competencies: null,
      start: true,
      register: false,
      register360: false,
      quiz: false,
      result: false,
      compselected: [],
      form_person: {},
      afirmations: '',
      resultado: '',
      form_test: '',
      showToast: false,
      message: "",
      labelType: "success",
      diagnostico: {}
    };
  },
  created() {
    this.competencies = this.competencias;
    this.competencies = this.competencies.map((el) => {
      el.visible = false;
      return el;
    });
  },
  methods: {
    clearMessage() {
      this.message = ""
    },
    registerUser($user) {
      if (this.compselected == '') {
        this.labelType = "danger"
        this.message = 'Debe seleccionar una competencia.'
      } else {
        if ($user) {
          this.storeUser($user);
        } else {
          this.start = false,
            this.register = true
        }
      }
    },
    async storePerson(data) {
      this.form_person = data
      let rt = route('test.store');
      let formData = new FormData();
      formData.append('name', this.form_person.name);
      formData.append('lastname', this.form_person.lastname);
      formData.append('email', this.form_person.email);
      formData.append('form_competencias', this.compselected);

      console.log(formData);
      const response = await axios.post(rt, formData)

      if (response.status == 200) {
        this.afirmations = response.data.data
        this.form_person.id = response.data.person.data.id
        this.form_test = response.data.test
        if (response.data.data.length == 0) {
          this.labelType = "error"
          this.message = 'No hay afirmaciones para esta competencia.'
        } else {
          this.register = false
          this.quiz = true
        }

      } else {
        this.labelType = "error"
        this.message = response.data['message']
      }

    },
    async storeUser(data) {
      this.form_person = data
      let rt = route('test.storeUser');
      let formData = new FormData();
      formData.append('id', data.id);
      formData.append('name', data.name);
      formData.append('email', data.email);
      formData.append('form_competencias', this.compselected);

      console.log(formData);
      const response = await axios.post(rt, formData)

      if (response.status == 200) {
        this.afirmations = response.data.data
        this.form_person.id = response.data.person
        this.form_test = response.data.test
        if (response.data.data.length == 0) {
          this.labelType = "error"
          this.message = 'No hay afirmaciones para esta competencia.'
        } else {
          this.start = false,
            this.quiz = true
        }

      } else {
        this.labelType = "error"
        this.message = response.data['message']
      }

    },
    processQuiz(data) {
      let post = route('quiz.calculate')
      axios.post(post, {
        'afirmations': data,
        'form_person': this.form_person,
        'form_test': this.form_test
      })
        .then(response => {
          if (response.status == 200) {
            this.resultado = response.data
            this.quiz = false
            this.result = true
          } else {
            this.labelType = "error"
            this.message = response.data['message']
          }
        })

      this.showResult = true
    },
    selectCompetencia(id) {
      if (!this.compselected.includes(id)) {
        this.compselected.push(id);
        console.log(this.compselected);
      } else {
        this.compselected.splice(this.compselected.indexOf(id), 1);
        console.log(this.compselected);
      }
    },
    sendItem(val) {
      this.modalContent = val;
    },
    // Funciones de Diagnosticos
    sendDiagnostico(data) {
      this.diagnostico = data
      if (data.diagnostico_360) {
        this.switchView('register360')
      } else {
        let form = {}
        form.diagnostico_id = data.id
        form.user_id = null
        this.storeDiagnostico(form)
      }
    },
    async storeDiagnostico(data) {
      console.log(data)
      this.form_person = data
      let rt = route('test.storeUserDiagnostico');
      let formData = new FormData();
      formData.append('user_id', data.user_id);
      formData.append('diagnostico_id', data.diagnostico_id);

      const response = await axios.post(rt, formData)
      if (response.status == 200) {
        this.afirmations = response.data.data
        this.form_person.id = response.data.person
        this.form_test = response.data.test
        if (response.data.data.length == 0) {
          this.labelType = "error"
          this.message = 'No hay afirmaciones para este Diagnostico.'
        } else {
          this.switchView('quiz')
        }

      } else {
        this.labelType = "error"
        this.message = response.data['message']
      }
    },
    switchView(view = '') {
      switch (view) {
        case 'register360':
          this.start = false
          this.register = false
          this.register360 = true
          this.quiz = false
          this.result = false
          break;
        
        case 'quiz':
          this.start = false
          this.register = false
          this.register360 = false
          this.quiz = true
          this.result = false
          break;

        default:
          this.start = true
          this.register = false
          this.register360 = false
          this.quiz = false
          this.result = false
          break;
      }
    }
  },
};
</script>

<style scoped>
.expand-enter-from,
.expand-leave-to {
  opacity: 0;
}

.expand-leave-active,
.expand-enter-active {
  transition: opacity 0.3s ease;
}
</style>