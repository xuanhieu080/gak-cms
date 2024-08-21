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
                    <a-menu-item key="1" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <HomeOutlined />
                        </div>
                        <span>Trang chủ</span>
                    </a-menu-item>
                    <a-menu-item key="2" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <ShopOutlined />
                        </div>
                        <span>Quản lý sản phẩm</span>
                    </a-menu-item>
                    <a-menu-item key="3" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <BankOutlined />
                        </div>
                        <span>Quản lý kho hàng</span>
                    </a-menu-item>
                    <a-menu-item key="4" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <DeliveredProcedureOutlined />
                        </div>
                        <span>Đơn chuyển kho - Giao khách</span>
                    </a-menu-item>
                    <a-menu-item key="5" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <CloudDownloadOutlined />
                        </div>
                        <span>Nhập kho - Điều chuyển kho</span>
                    </a-menu-item>
                    <a-menu-item key="6" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <TeamOutlined />
                        </div>
                        <span>Quản lý danh sách nhân viên</span>
                    </a-menu-item>
                    <a-menu-item key="7" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <ToolOutlined />
                        </div>
                        <span>Cấu hình</span>
                    </a-menu-item>
                    <a-menu-item key="8" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <AppstoreOutlined />
                        </div>
                        <span>Phân quyền</span>
                    </a-menu-item>
                    <a-menu-item key="9" :class="collapsed ? 'hide-title' : ''">
                        <div class="flex items-center">
                            <GoldOutlined />
                        </div>
                        <span>Quản lý nguyên liệu</span>
                    </a-menu-item>
                    <a-menu-item
                        key="10"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <div class="flex items-center">
                            <CalculatorOutlined />
                        </div>
                        <span>Quản lý đơn vị</span>
                    </a-menu-item>
                    <a-menu-item
                        key="11"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <div class="flex items-center">
                            <FieldNumberOutlined />
                        </div>
                        <span>Quản lý nhóm thuộc tính</span>
                    </a-menu-item>
                    <a-menu-item
                        key="12"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <div class="flex items-center">
                            <ExceptionOutlined />
                        </div>
                        <span>Quản lý công nợ</span>
                    </a-menu-item>
                    <a-menu-item
                        key="13"
                        :class="collapsed ? 'hide-title' : ''"
                    >
                        <div class="flex items-center">
                            <ProjectOutlined />
                        </div>
                        <span
                            >Quản lý hóa đơn - Thống kê doanh thu - Báo
                            giá</span
                        >
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
                    margin: '24px 16px',
                    padding: '24px',
                    minHeight: '100vh',
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
    FieldNumberOutlined,
    CalculatorOutlined,
    ExceptionOutlined,
    DeliveredProcedureOutlined,
    ProjectOutlined,
    SisternodeOutlined,
    CloudDownloadOutlined,
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
<style lang="scss">
#gak-cms-dashboard {
    .ant-layout-sider {
        .ant-menu-item {
            height: auto;
            .ant-menu-title-content {
                @apply flex gap-2;
                overflow: visible;
                white-space: pre-line;
                line-height: 1.4;
                padding: 16px 0;
            }

            &.hide-title {
                .ant-menu-title-content {
                    @apply flex gap-2;
                    overflow: hidden;
                    white-space: nowrap;

                    padding: 0;
                }
            }
        }
    }
}
</style>
