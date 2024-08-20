<template>
    <a-layout id="gak-cms-dashboard">
        <a-layout-header :style="headerStyle">
            <menu-unfold-outlined
                v-if="collapsed"
                class="trigger"
                @click="() => (collapsed = !collapsed)"
            />
            <menu-fold-outlined
                v-else
                class="trigger"
                @click="() => (collapsed = !collapsed)"
            />
            <div class="logo">GAK Logo</div>

            <div class="user-logo ml-auto flex items-center justify-center">
                <a-dropdown>
                    <template #overlay>
                        <a-menu @click="handleMenuClick">
                            <a-menu-item key="1">
                                <UserOutlined />
                                Đến trang cá nhân
                            </a-menu-item>
                            <a-menu-item key="2">
                                <UserOutlined />
                                Trang chủ
                            </a-menu-item>
                            <a-menu-item key="3"  @click.stop.prevent="handleLogout">
                                <LogoutOutlined />
                                Đăng xuất
                            </a-menu-item>
                        </a-menu>
                    </template>
                    <a-avatar
                        size="large"
                        :style="{
                            display: 'flex',
                            justifyContent: 'center',
                            alignItems: 'center',
                        }"
                    >
                        <template #icon><UserOutlined /></template>
                    </a-avatar>
                </a-dropdown>
            </div>
        </a-layout-header>
        <a-layout>
            <a-layout-sider
                v-model:collapsed="collapsed"
                :trigger="null"
                collapsible
            >
                <a-menu
                    v-model:selectedKeys="selectedKeys"
                    theme="dark"
                    mode="inline"
                >
                    <a-menu-item key="1">
                        <user-outlined />
                        <span>Dashboard</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <video-camera-outlined />
                        <span>Child note 1</span>
                    </a-menu-item>
                    <a-menu-item key="3">
                        <upload-outlined />
                        <span>Child note 2</span>
                    </a-menu-item>
                </a-menu>
            </a-layout-sider>
            <a-layout-content
                :style="{
                    margin: '24px 16px',
                    padding: '24px',
                    background: '#fff',
                    minHeight: '280px',
                }"
            >
                <slot></slot>
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>
<script setup>
import { ref } from "vue";
import {
    UserOutlined,
    VideoCameraOutlined,
    UploadOutlined,
    MenuUnfoldOutlined,
    MenuFoldOutlined,
    LogoutOutlined,
} from "@ant-design/icons-vue";
import { useAuthStore } from "@/stores/auth";


const authStore = useAuthStore();

const selectedKeys = ref(["1"]);
const collapsed = ref(false);
const headerStyle = {
    display: "flex",
    padding: "16px",
    alignItems: "center",
    gap: "16px",
    backgroundColor: "#fff",
};

function handleLogout() {
    authStore.logout();
}
</script>

<style lang="scss" scoped>
#gak-cms-dashboard {
    min-height: 100vh;

    .trigger {
        flex-shrink: 0;
        flex-grow: 0;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.3s;
        &:hover {
            color: #1890ff;
        }
    }
    .logo {
        height: 32px;
        background: #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
}
.site-layout {
    .site-layout-background {
        background: #fff;
    }
}
</style>
