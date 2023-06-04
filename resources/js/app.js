import './bootstrap';



import { createApp } from 'vue'
//import ExampleComponent from './components/ExampleComponent.vue'
import RegisterComponent from './components/RegisterComponent.vue'

const app = createApp({})

//app.component('example-component', ExampleComponent)
app.component('register-component', RegisterComponent)

app.mount('#register-component')




import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
