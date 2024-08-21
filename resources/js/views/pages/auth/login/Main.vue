<template>
    <Auth>
        <h2 class="font-bold text-2xl text-[#002D74]">Đăng nhập</h2>
        <p class="text-sm mt-4 mb-4 text-[#002D74]">
            Nếu bạn đã là thành viên, vui lòng đăng nhập.
        </p>
        <LoginForm/>
        <div class="mt-5 text-sm border-b border-[#002D74] py-4 text-[#002D74]">
            <router-link to="/forgot-password" class="text-sm base-link">
                Quên mật khẩu
            </router-link>
        </div>
        <div class="mt-3 text-sm flex justify-between items-center text-[#002D74]">
            <p>Chưa có tài khoản?</p>
            <router-link to="/register" class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">
                Đăng ký
            </router-link>
        </div>
    </Auth>
</template>

<script setup>
import {default as LoginForm} from "@/views/pages/auth/login/Form";

import Auth from "@/views/layouts/Auth";
</script>
