import './bootstrap';
import { createApp } from "vue";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
// import ExampleComponent from "./components/ExampleComponent.vue";
// import AnalysisComponent from "./components/AnalysisComponent.vue"
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

createInertiaApp({
	resolve: (name) =>
		resolvePageComponent(
			`./Pages/${name}.vue`,
			import.meta.glob("./Pages/**/*.vue")
		),
	setup({ el, App, props, plugin }) {
		createApp({ render: () => h(App, props) })
			.use(plugin)
			.mount(el);
	},
});

// const app = createApp({});
// app.component("analysis-component", AnalysisComponent);
// app.mount("#app");



