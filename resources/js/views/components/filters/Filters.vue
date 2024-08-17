<template>
    <div class="relative z-10 p-4 md:p-8 bg-gray-50 rounded mb-2">
        <label class="absolute text-sm leading-snug text-gray-900 cursor-pointer hover:text-gray-700 top-2.5 right-3.5" @click="onClear">{{ $props.clear_text }}</label>
        <slot></slot>
    </div>
</template>

<script>
import {defineComponent} from "vue";

export default defineComponent({
    props: {
        clear_text: {
            type: String,
            required: false,
            default: "Xoá tất cả",
        }
    },
    emits: ['clear'],
    setup(props, {emit}) {

        function onClear() {
            emit("clear");
        }

        return {
            onClear
        }
    }
});
</script>
