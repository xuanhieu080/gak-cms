<script setup>
import Footer from '@/views/layouts/Footer.vue';
import Header from '@/views/layouts/Header.vue';
import Sidebar from '@/views/layouts/Sidebar.vue';

import {useStore} from '@/stores/sidebar';
import {useGlobalStateStore} from '@/stores/global';
import {storeToRefs} from 'pinia';

const state = useStore();
const {isToggleDesktop, isSideMenuOpen} = storeToRefs(state);
import {useAuthStore} from "@/stores/auth";
import Alert from "@/views/components/Alert.vue";
import Notify from "@/views/components/Notify.vue";
import {onMounted} from "vue";
import {useAlertStore} from "@/stores";
const authStore = useAuthStore();
const globalStore = useGlobalStateStore();

onMounted(() => {
    
})

</script>

<template>
    <div>
      <router-view></router-view>
    </div>
</template>

<style lang="scss" scoped>
@media screen and (min-width: 768px) {
.main {padding-left: 16rem}
.main.main-full {padding-left: 75px}
}
</style>
