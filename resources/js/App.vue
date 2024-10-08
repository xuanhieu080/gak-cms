<template>
  <div class="bg-gray-300" v-if="authStore.user && authStore.user.hasOwnProperty('id')">
    <Index/>
  </div>
  <template v-else>
    <router-view/>
  </template>
</template>

<script>
import {computed, onBeforeMount, reactive} from "vue";

import Menu from "@/views/layouts/Menu";
import Icon from "@/views/components/icons/Icon";
import AvatarIcon from "@/views/components/icons/Avatar";
import {useAuthStore} from "@/stores/auth";
import {useGlobalStateStore} from "@/stores";
import {useRoute} from "vue-router";
import {useAlertStore} from "@/stores";
import {getAbilitiesForRoute} from "@/helpers/routing";
import Index from "@/views/layouts/Index.vue";
import {useStore} from "@/stores/sidebar";

export default {
  name: "app",
  components: {
    Index,
    AvatarIcon,
    Menu,
    Icon
  },
  setup() {

    const alertStore = useAlertStore();
    const authStore = useAuthStore();
    const globalStateStore = useGlobalStateStore();
    const route = useRoute();
    const stateStore = useStore();

    const isLoading = computed(() => {
      var value = false;
      for (var i in globalStateStore.loadingElements) {
        if (globalStateStore.loadingElements[i]) {
          value = true;
          break;
        }
      }
      return value || globalStateStore.isUILoading;
    })

    const state = reactive({
      mainMenu: [
        {
          name: "Trang chủ",
          icon: 'tachometer',
          showDesktop: true,
          showMobile: true,
          requiresAbility: false,
          to: '/',
        },
        {
          name: "Tài khảon",
          icon: 'users',
          showDesktop: true,
          showMobile: true,
          requiresAbility: getAbilitiesForRoute(['users.list', 'users.create', 'users.edit']),
          to: '/users/list',
          children: [
            {
              name: "Danh sách",
              icon: '',
              showDesktop: true,
              showMobile: true,
              requiresAbility: getAbilitiesForRoute('users.list'),
              to: '/users/list',
            },
            {
              name: "Thêm mới",
              icon: '',
              showDesktop: true,
              showMobile: true,
              requiresAbility: getAbilitiesForRoute('users.create'),
              to: '/users/create',
            }
          ]
        },
        {
          name: "Đăng xuất",
          icon: 'sign-out',
          showDesktop: false,
          showMobile: true,
          showIfRole: false,
          onClick: onLogout,
          to: '',
        }
      ],
      headerLeftLink: {
        name: "Thêm",
        icon: 'plus',
        to: '',
        href: '#',
      },
      footerLeftLink: {
        name: "Tài liệu",
        icon: 'paperclip',
        to: '',
        href: '#',
      },
      isAccountDropdownOpen: false,
      isMobileMenuOpen: false,
      currentExpandedMenuItem: null,
      app: window.AppConfig,
    });

    function onLogout() {
      authStore.logout()
    }

    onBeforeMount(() => {
      stateStore.setThemeToLocalStorage('light')
      if (route.query.hasOwnProperty('verified') && route.query.verified) {
        alertStore.success("Xác minh email thành công!");
      }
    });

    return {
      state,
      authStore,
      globalStateStore,
      onLogout,
      isLoading,
    }
  }
};
</script>
