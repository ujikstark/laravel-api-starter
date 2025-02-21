require('./../../bootstrap');

import { createApp, ref, onMounted } from "vue";
import Form from "./../../plugins/form";

const app = createApp({
    setup() {
        const form = ref({});

        onMounted(() => {
            form.value = new Form("auth");
        });

        const onSubmit = () => {

            if (form.value) {
                form.value.submit();
            }
        };

        return { form, onSubmit };
    },
});

app.mount("#app");
