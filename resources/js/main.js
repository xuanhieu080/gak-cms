import {createApp} from 'vue'
import { createPinia } from 'pinia'
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';

import router from "@/router";
import App from "@/App";
import Notifications from 'notiwind'

const app = createApp(App)

app.use(createPinia());
app.use(CkeditorPlugin);

app.use(router);
app.use(Notifications);
app.mount('#app');




