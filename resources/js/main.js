import {createApp} from 'vue'
import { createPinia } from 'pinia'
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';
import { Chart, Title, Tooltip, Legend,ArcElement, BarElement, CategoryScale, LinearScale, PointElement, LineElement } from 'chart.js';

import router from "@/router";
import App from "@/App";
import Notifications from 'notiwind';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';
const app = createApp(App)
Chart.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, PointElement, LineElement, ArcElement);

app.use(createPinia());
app.use(CkeditorPlugin);

app.use(router);
app.use(Notifications);
app.use(Antd);
app.mount('#app');




