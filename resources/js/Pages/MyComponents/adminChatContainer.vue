<template>
    <div class="admin-chat-wrapper" data-closed="true">
        <adminChat :room="room" :user="user" @adminMessageReceived="showNotification"/>
    </div>
    <button class="admin-chat-button" @click="toggleChat">
        <span class="material-symbols-outlined">
            forum
        </span>
    </button>
    <button class="admin-chat-button-close" data-button-closed="true" @click="toggleChat">
        <span class="material-symbols-outlined">
            cancel
        </span>
    </button>
    <div v-if="chatNotifications" class="notifications-container">
        <p>{{ chatNotifications }}</p>
    </div>
</template>

<script>
import adminChat from "@/Pages/MyComponents/adminChat.vue";
    export default {
        components: {
            adminChat
        },
        props: ['room', 'user'],
        data: function () {
            return {
                chatStatus : false,
                chatNotifications : 0,
            }
        },
        methods: {
            toggleChat() {
                const wrapper = document.querySelector('.admin-chat-wrapper');
                const buttonClosed = document.querySelector('.admin-chat-button-close');
                const closed = wrapper.getAttribute('data-closed');
                if (closed === 'true') {
                    wrapper.setAttribute('data-closed', 'false');
                    buttonClosed.setAttribute('data-button-closed', 'false');
                    this.chatStatus = true;
                    this.chatNotifications = 0;
                } else {
                    wrapper.setAttribute('data-closed', 'true');
                    buttonClosed.setAttribute('data-button-closed', 'true');
                    this.chatStatus = false;
                }
            },
            showNotification() {
                if (!this.chatStatus) {
                    this.chatNotifications++;
                }
            }
        },
    }
</script>