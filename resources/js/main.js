import {createApp} from 'vue'
import { createPinia } from 'pinia'
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';

import router from "@/router";
import App from "@/App";
import Notifications from 'notiwind';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';
const app = createApp(App)

app.use(createPinia());
app.use(CkeditorPlugin);

app.use(router);
app.use(Notifications);
app.use(Antd);
app.mount('#app');




