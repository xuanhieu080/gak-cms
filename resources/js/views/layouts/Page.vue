<template>
    <a-layout id="gak-cms-dashboard" hasSider="true">
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
                        <a-menu>
                            <a-menu-item key="profile">
                                <router-link
                                    :to="{ name: 'profile' }"
                                    class="flex items-center gap-1"
                                >
                                    <UserOutlined />
                                    Đến trang cá nhân
                                </router-link>
                            </a-menu-item>
                            <a-menu-item key="2">
                                <UserOutlined />
                                Cài đặt đăng nhập
                            </a-menu-item>
                            <a-menu-item
                                key="3"
                                @click.stop.prevent="handleLogout"
                            >
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
                    <a-menu-item
                        key="home"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <router-link
                            :to="{ name: 'home' }"
                            class="flex items-center gap-2"
                        >
                            <div class="flex items-center">
                                <HomeOutlined />
                            </div>
                            <span>Trang chủ</span>
                        </router-link>
                    </a-menu-item>

                    <a-menu-item key="2" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <ShopOutlined />
                        </div>
                        <span>Quản lý sản phẩm</span>
                    </a-menu-item>
                    <a-sub-menu
                        key="sub1"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <template #title>
                            <div
                                class="flex items-center w-full"
                                :class="collapsed ? 'justify-center' : ''"
                            >
                                <BankOutlined />
                                <span :class="collapsed ? '!hidden' : ''">
                                    Kho
                                </span>
                            </div>
                        </template>
                        <a-menu-item
                            key="3"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <router-link
                                :to="{ name: 'warehouse-index' }"
                                class="flex items-center gap-2"
                            >
                                <span>Quản lý kho hàng</span>
                            </router-link>
                        </a-menu-item>
                        <a-menu-item
                            key="4"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <span>Đơn chuyển kho - Giao khách</span>
                        </a-menu-item>
                        <a-menu-item
                            key="5"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <span>Nhập kho - Điều chuyển kho</span>
                        </a-menu-item>
                    </a-sub-menu>
                    <a-sub-menu
                        key="sub-2"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <template #title>
                            <div
                                class="flex items-center w-full"
                                :class="collapsed ? 'justify-center' : ''"
                            >
                                <GoldOutlined />
                                <span :class="collapsed ? '!hidden' : ''">
                                    Quản lý Nguyên liệu
                                </span>
                            </div>
                        </template>
                        <a-menu-item
                            key="9"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <router-link
                                :to="{ name: 'management-materials' }"
                                class="flex items-center gap-2"
                            >
                                <span>Nguyên liệu</span>
                            </router-link>
                        </a-menu-item>
                        <a-menu-item
                            key="10"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <router-link
                                :to="{ name: 'management-units' }"
                                class="flex items-center gap-2"
                            >
                                <span>Đơn vị tính</span>
                            </router-link>
                        </a-menu-item>
                        <a-menu-item
                            key="11"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <span>Nhóm thuộc tính</span>
                        </a-menu-item>
                    </a-sub-menu>
                    <a-sub-menu
                        key="sub-3"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <template #title>
                            <div
                                class="flex items-center w-full"
                                :class="collapsed ? 'justify-center' : ''"
                            >
                                <ExceptionOutlined />
                                <span :class="collapsed ? '!hidden' : ''">
                                    Công nợ - Hóa Đơn
                                </span>
                            </div>
                        </template>
                        <a-menu-item
                            key="12"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <span>Công nợ</span>
                        </a-menu-item>
                        <a-menu-item
                            key="13"
                            :class="collapsed ? 'hide-title' : ''"
                        >
                            <span>
                                Hóa đơn - Thống kê doanh thu - Báo giá
                            </span>
                        </a-menu-item>
                    </a-sub-menu>

                    <a-menu-item key="6" :class="collapsed ? 'hide-title' : ''">
                        <router-link
                            :to="{ name: 'users-management' }"
                            class="flex items-center gap-2"
                        >
                            <div class="flex items-center">
                                <TeamOutlined />
                            </div>
                            <span>Quản lý danh sách nhân viên</span>
                        </router-link>
                    </a-menu-item>
                    <a-menu-item key="7" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <ToolOutlined />
                        </div>
                        <span>Cấu hình</span>
                    </a-menu-item>

                    <a-menu-item
                        key="roles"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <router-link
                            :to="{ name: 'roles' }"
                            class="flex items-center gap-2"
                        >
                            <div class="flex items-center">
                                <AppstoreOutlined />
                            </div>
                            <span>Phân quyền</span>
                        </router-link>
                    </a-menu-item>
                    <a-menu-item
                        key="14"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <div class="flex items-center">
                            <SisternodeOutlined />
                        </div>
                        <span>Chức năng khác</span>
                    </a-menu-item>
                </a-menu>
            </a-layout-sider>
            <a-layout-content
                :style="{
                    padding: '16px 24px',
                    overflow: 'auto',
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
    HomeOutlined,
    ShopOutlined,
    BankOutlined,
    TeamOutlined,
    ToolOutlined,
    AppstoreOutlined,
    GoldOutlined,
    ExceptionOutlined,
    SisternodeOutlined,
    MenuUnfoldOutlined,
    MenuFoldOutlined,
    LogoutOutlined,
} from "@ant-design/icons-vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
const authStore = useAuthStore();
const router = useRouter();
const selectedKeys = ref([router.currentRoute.value.name]);
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
    height: 100vh;

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
<style lang="scss">
#gak-cms-dashboard {
    .ant-layout-sider {
        width: 250px !important;
        min-width: 250px !important;
        max-width: 250px !important;
        &.ant-layout-sider-collapsed {
            width: 60px !important;
            min-width: 60px !important;
            max-width: 60px !important;
        }
        .ant-menu-item,
        .ant-menu-submenu {
            height: auto;
            .ant-menu-title-content {
                @apply flex gap-2;
                overflow: visible;
                white-space: pre-line;
                line-height: 1.4;
                padding: 6px 0;
            }
            .ant-menu-submenu-title {
                padding: 0;
                height: auto;
            }

            &.hide-title {
                .ant-menu-title-content {
                    @apply flex gap-2;
                    overflow: hidden;
                    white-space: nowrap;
                    padding: 0;
                }
                .ant-menu-submenu-title {
                    .ant-menu-title-content {
                        width: 100%;
                    }
                }
            }
        }
    }
}
</style>
