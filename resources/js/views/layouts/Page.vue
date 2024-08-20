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
} from "@ant-design/icons-vue";
const selectedKeys = ref(["1"]);
const collapsed = ref(false);
const headerStyle = {
    display: "flex",
    padding: 0,
    backgroundColor: "#fff",
};
</script>

<style lang="scss" scoped>
#gak-cms-dashboard {
    min-height: 100vh;

    .trigger {
        font-size: 18px;
        line-height: 64px;
        padding: 0 24px;
        cursor: pointer;
        transition: color 0.3s;
        &:hover {
            color: #1890ff;
        }
    }
    .logo {
        height: 32px;
        background: #f1f1f1;
        margin: 16px 0;
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
