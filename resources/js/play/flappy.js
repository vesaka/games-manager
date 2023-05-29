import { createApp } from 'vue';
import { createPinia } from 'pinia';
import env from '$flappy/bootstrap/env';
import App from '$flappy/components/pages/Playground.vue';


createApp(App)
    .use(env)
    .use(createPinia())
    .mount('#app');

