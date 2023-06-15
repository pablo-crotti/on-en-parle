<template>
    <div class="container">
        <h1>ChatRecu</h1>
        <div class="column" v-for="status in [0, 1]" :key="status">
            <div
                class="dropzone"
                :id="`column-${status}`"
                @drop="drop($event, status)"
                @dragover.prevent
            >
                <chat-message
                    v-for="message in messages.filter(
                        (m) => m.status === status
                    )"
                    :key="message.id"
                    :message="message"
                    @dragstart="drag($event, message.id)"
                />
            </div>
        </div>
    </div>
</template>

<script>
/**
 * Component: ChatMessageContainer
 * Description: Container component for managing chat messages.
 */
import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";

export default {
    components: {
        ChatMessage,
    },

    props: {
        /**
         * The initial array of chat messages.
         */
        initialMessages: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            /**
             * The array of chat messages.
             */
            messages: this.initialMessages,
        };
    },

    methods: {
        /**
         * Handles the drag event for a chat message.
         * @param {Event} event - The drag event.
         * @param {number} messageId - The ID of the dragged message.
         */
        drag(event, messageId) {
            event.dataTransfer.setData("text", messageId);
        },

        /**
         * Handles the drop event for a chat message.
         * Updates the status of the dropped message and sends a request to the server.
         * @param {Event} event - The drop event.
         * @param {string} status - The status to update the message to.
         */
        async drop(event, status) {
            const messageId = parseInt(event.dataTransfer.getData("text"));
            const message = this.messages.find((m) => m.id === messageId);

            if (message) {
                message.status = status;
                await axios.post(`/chatroom/message/${message.id}/update`, {
                    status,
                });
            }
        },
    },
};
</script>

<style>
.container {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    background-color: aliceblue;
}
.box {
    width: 20%;
    height: 50%;
    border: solid 1px black;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
}
.item {
    padding: 20px;
    color: white;
    width: 100%;
}
</style>
