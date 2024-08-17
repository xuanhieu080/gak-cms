<template>
    <Auth>
        <h2 class="font-bold text-2xl text-[#002D74]">Đặt lại mật khẩu</h2>
        <p class="text-sm mt-4 mb-4 text-[#002D74]">
          Điền vào biểu mẫu để đặt lại mật khẩu của bạn.
        </p>
        <ResetPasswordForm/>
        <div class="mt-5 text-sm flex justify-between items-center text-[#002D74] border-t border-[#002D74] pt-3">
            <p>Đẵ có tài khoản</p>
            <router-link to="/login" class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">
                Đăng nhập
            </router-link>
        </div>
    </Auth>
</template>

<script>
import {default as ResetPasswordForm} from "@/views/pages/auth/reset-password/Form";
import Auth from "@/views/layouts/Auth";

export default {
    name: "ResetPassword",
    components: {
        Auth,
        ResetPasswordForm
    },
};
</script>
