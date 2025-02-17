import { createApp, onMounted } from 'vue';
import './bootstrap';

const app = createApp({
    setup() {
        onMounted(() => {
            console.log('halo from vue');


        });
    }
});

app.mount('#app');
