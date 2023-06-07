<template>
    <div class="admin-chat-wrapper" data-closed="true">
        <adminChat />
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
</template>

<script>
import adminChat from "@/Pages/MyComponents/adminChat.vue";
    export default {
        components: {
            adminChat
        },
        props: ['room'],
        methods: {
            toggleChat() {
                const wrapper = document.querySelector('.admin-chat-wrapper');
                const button = document.querySelector('.admin-chat-button');
                const buttonClosed = document.querySelector('.admin-chat-button-close');
                const closed = wrapper.getAttribute('data-closed');
                if (closed === 'true') {
                    wrapper.setAttribute('data-closed', 'false');
                    buttonClosed.setAttribute('data-button-closed', 'false');
                } else {
                    wrapper.setAttribute('data-closed', 'true');
                    buttonClosed.setAttribute('data-button-closed', 'true');
                }
            }
        },
    }
</script>