<script setup lang="ts">

</script>

<template>
    <NotificationGroup group="failed">
        <div
            class="notify fixed z-auto flex items-start justify-end p-6 px-4 py-6"
        >
            <div class="w-full max-w-sm">
                <Notification
                    v-slot="{ notifications, close, hovering }"
                    enter="transform ease-out duration-300 transition"
                    enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    enter-to="translate-y-0 opacity-100 sm:translate-x-0"
                    leave="transition ease-in duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    move="transition duration-500"
                    move-delay="delay-300"
                >
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="relative px-4 py-3 mt-4 text-red-700 bg-red-100 border border-red-400 rounded"
                        role="alert"
                        @mouseover="hovering(notification.id, true)"
                        @mouseleave="hovering(notification.id, false)"
                    >
                        <!--                        <strong class="font-bold mr-4">Thông báo!</strong>-->
                        <span class="block sm:inline">{{ notification.text }}</span>

                        <button @click="close(notification.id)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg
                                class="w-6 h-6 text-red-500 fill-current"
                                role="button"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <title>Xoá</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                />
                            </svg>
                        </button>
                    </div>
                </Notification>
            </div>
        </div>
    </NotificationGroup>

    <NotificationGroup group="success">
        <div
            class="notify fixed z-auto flex items-start justify-end p-6 px-4 py-6"
        >
            <div class="w-full max-w-sm">
                <Notification
                    v-slot="{ notifications, close, hovering }"
                    enter="transform ease-out duration-300 transition"
                    enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    enter-to="translate-y-0 opacity-100 sm:translate-x-0"
                    leave="transition ease-in duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                    move="transition duration-500"
                    move-delay="delay-300"
                >
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="relative px-4 py-3 mt-4 text-red-700 bg-green-100 border border-green-400 rounded"
                        role="alert"
                        @mouseover="hovering(notification.id, true)"
                        @mouseleave="hovering(notification.id, false)"
                    >
                        <!--                        <strong class="font-bold">Thông báo!</strong>-->
                        <span class="block sm:inline">{{ notification.text }}</span>

                        <button @click="close(notification.id)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg
                                class="w-6 h-6 text-red-500 fill-current"
                                role="button"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <title>Xoá</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
                                />
                            </svg>
                        </button>
                    </div>
                </Notification>
            </div>
        </div>
    </NotificationGroup>
</template>

<style scoped>
.notify {
    position: absolute;
    right: 2rem;
    top: 4rem;
    z-index: 99999999;
}
</style>
