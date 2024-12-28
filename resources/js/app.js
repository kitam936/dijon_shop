import './bootstrap';
import { createApp } from "vue";
import ExampleComponent from "./components/ExampleComponent.vue";
import AnalysisComponent from "./components/AnalysisComponent.vue"
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
// app.component("example-component", ExampleComponent);
app.component("analysis-component", AnalysisComponent);
app.mount("#app");



