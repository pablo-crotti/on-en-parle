<template>
    <AppLayout
        :title="room ? `On en parle | Questions ${room.name}` : 'On en parle'"
    >
        <div v-if="room" class="chat-wrapper">
            <div class="chat-transmission">
                <div class="program-detail">
                    <div class="transmission-card-container">
                        <div class="card">
                            <transmission-card :room="room" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="chat-container">
                <message-container :messages="messages" />
            </div>
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: Layout component for the admin section.
 *
 * Component: MessageContainer
 * Description: Container component for displaying chat messages.
 *
 * Component: InputMessage
 * Description: Component for inputting and sending chat messages.
 *
 * Component: ChatRoomSelection
 * Description: Component for selecting a chat room.
 *
 * Component: TransmissionCard
 * Description: A custom component for displaying transmission details.
 */

import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import MessageContainer from "@/Pages/Chat/messageContainer.vue";
import InputMessage from "@/Pages/Chat/inputMessage.vue";
import ChatRoomSelection from "@/Pages/Chat/chatRoomSelection.vue";
import TransmissionCard from "@/Pages/MyComponents/admin-transmission-card.vue";
import axios from "axios";

export default {
    components: {
        AppLayout,
        MessageContainer,
        InputMessage,
        ChatRoomSelection,
        TransmissionCard,
    },
    data() {
        return {
            messages: [],
            roomIdLink: window.location.href.split("/").pop(),
            room: null,
        };
    },
    methods: {
        /**
         * Fetches the chat room details.
         */
        getRoom() {
            axios.get("/chat/room/" + this.roomIdLink).then((response) => {
                this.room = response.data;
            });
        },
        /**
         * Fetches the messages for the chat room.
         */
        getMessages() {
            axios
                .get("/chat/room/" + this.roomIdLink + "/messages")
                .then((response) => {
                    this.messages = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    watch: {
        room() {
            this.getMessages();
        },
    },
    created() {
        // Fetch the chat room and messages on component creation
        this.getRoom();
        this.getMessages();

        // Listen for new likes in the chat room
        const likesChannel = Echo.channel("like." + this.roomIdLink);
        likesChannel.listen(".like.new", (e) => {
            this.getMessages();
        });

        // Listen for new chat messages in the chat room
        const chatChannel = Echo.channel("chat." + this.roomIdLink);
        chatChannel.listen(".message.new", (e) => {
            this.getMessages();
        });

        // Listen for live status updates
        const liveChannel = Echo.channel("live.status");
        liveChannel.listen(".live.status.new", (e) => {
            this.getRoom();
        });

        // Listen for room updates
        const roomChannel = Echo.channel("room.update");
        roomChannel.listen(".event.on.room", (e) => {
            this.getRoom();
        });
    },
};
</script>
