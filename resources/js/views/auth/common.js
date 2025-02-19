require('./../../bootstrap');

import { createApp } from "vue";
import Form from "./../../plugins/form";

const app = createApp({
    data() {
        return {
            form: new Form("auth"),
        };
    },

    methods: {
        onSubmit() {
            this.form.submit();
        },
    },

});

app.mount("#app");
