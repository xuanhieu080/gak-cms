<template>
  <div v-if="alertStore.hasAlert()" class="container-alert">
    <div v-if="alertStore" :class="wrapperClass" role="alert">
      <template v-if="alertStore.isError()">
        <template v-if="alertStore.hasMultiple()">
          <strong class="font-bold mr-2">Ồ!</strong>
          <span class="block sm:inline">Vui lòng sửa các lỗi sau</span>
          <ul class="mt-2 list-disc pl-5" key="error-list">
            <li v-for="key in errorKeys" :key="key">
              {{ getMessageError(key) }}
            </li>
          </ul>
          <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="removeMessage">
                    <Icon name="times"/>
                </span>
        </template>
        <template v-else>
          <strong class="font-bold mr-2">Ồ!</strong>
          <span class="block sm:inline">{{
              Array.isArray(alertStore.messages[0]) ? alertStore.messages[0][0] : alertStore.messages[0]
            }}</span>
          <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="removeMessage">
                        <Icon name="times"/>
                    </span>
        </template>
      </template>
      <template v-else>
        <strong class="font-bold mr-2">Thành công!</strong>
        <span class="block sm:inline">{{
            Array.isArray(alertStore.messages[0]) ? alertStore.messages[0][0] : alertStore.messages[0]
          }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" @click="removeMessage">
                <Icon name="times"/>
            </span>
      </template>
    </div>
  </div>
</template>

<script>
import {computed, defineComponent, watch} from "vue";
import Icon from "@/views/components/icons/Icon";
import {useAlertStore} from "@/stores";

export default defineComponent({
  components: {Icon},
  inheritAttrs: true,
  setup(props, {emit}) {

    const alertStore = useAlertStore();
    const wrapperClass = computed(() => {
      if (alertStore.isSuccess()) {
        return "border px-4 py-4 rounded relative text-sm text-green-700 bg-green-100 border-green-400";
      } else {
        return "border px-4 py-3 rounded relative text-sm text-red-700 bg-red-100 border-red-400";
      }
    })

    const errorKeys = computed(() => {
      if (!alertStore.messages || getType(alertStore.messages) === "string") {
        return null;
      }
      return Object.keys(alertStore.messages);
    })

    function getType(obj) {
      return Object.prototype.toString.call(obj).slice(8, -1).toLowerCase();
    }

    function getMessageError(key) {
      return alertStore.messages[key][0];
    }

    function removeMessage() {
      alertStore.clear();
    }

    watch(() => alertStore.errors,
        () => {
          if (alertStore.messages.length > 0) {
            setTimeout(() => {
              alertStore.clear();
            }, 5000)
          }
        })

    return {
      alertStore,
      wrapperClass,
      errorKeys,
      getType,
      getMessageError,
      removeMessage
    }
  }
});
</script>
<style scoped>
.container-alert {
  position: fixed;
  right: 2rem;
  top: 6px;
  z-index: 99999999;
}
</style>
