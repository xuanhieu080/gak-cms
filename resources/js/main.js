import {createApp} from 'vue'
import { createPinia } from 'pinia'
import CKEditor from '@ckeditor/ckeditor5-vue';

import router from "@/router";
import App from "@/App";
import Notifications from 'notiwind'

const app = createApp(App)

app.use(createPinia());
app.use(CKEditor);

app.use(router);
app.use(Notifications);
app.mount('#app');




