import { createApp } from 'vue';
import { createPinia } from 'pinia';
import env from '$toto/bootstrap/env';
import App from '$toto/components/pages/Playground.vue';

createApp(App)
    .use(env)
    .use(createPinia())
    .mount('#app');

