<template>
    <Panel title="Cài đặt Hình đại diện">
        <Form @submit.prevent="onSubmit">
            <FileInput name="file" label="Ảnh đại diện" v-model="form.file"
                       :required="false"
                       error-input="avatar"
                       @clear="form.file = ''"
                       accept="image/*"
                       class="mb-4"></FileInput>
            <Button type="submit" label="Tải tệp lên"/>
        </Form>
    </Panel>
</template>

<script setup>
import {reactive, defineComponent} from "vue";
import {useAlertStore, useAuthStore} from "@/stores";
import Button from "@/views/components/input/Button";
import FileInput from "@/views/components/input/FileInput";
import Panel from "@/views/components/Panel";
import Form from "@/views/components/Form.vue";
const emit = defineEmits( ['done', 'error'])

const alertStore = useAlertStore();
const authStore = useAuthStore();
const form = reactive({
  file: null,
})

function onChange(event) {
  alertStore.clear();
  form.file = event.target.files[0];
}

function onSubmit() {
  authStore.updateAvatar(authStore.user.id, {'avatar': form.file}).then(() => {
    emit('done');
  }).catch((error) => {
    emit('error');
  });
}
</script>
