<template>
    <Alert class="mb-4"></Alert>
    <Form id="login-form" @submit.prevent="onFormSubmit">
        <TextInput type="email" label="Email" :required="true" name="email" v-model="form.email"
                   autocomplete="email" error-input="email" class="mb-2"/>
        <TextInput type="password" label="Mật khẩu" :required="true" name="password"
                   v-model="form.password" error-input="password" class="mb-4"/>
        <div class="text-center">
            <Button type="submit" label="Đăng nhập"/>
        </div>
    </Form>
</template>

<script setup>

import {reactive} from "vue";
import {useAuthStore} from "@/stores/auth";
import Button from "@/views/components/input/Button";
import TextInput from "@/views/components/input/TextInput";
import Alert from "@/views/components/Alert";
import Form from "@/views/components/Form";

const emits = defineEmits(['error'])

const authStore = useAuthStore();
const form = reactive({
  email: null,
  password: null,
})

function onFormSubmit() {
  const payload = {
    email: form.email,
    password: form.password,
  };
  authStore.login(payload)
}
</script>
