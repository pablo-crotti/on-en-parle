<script>
import AppLayout from '@/Layouts/AppLayoutUser.vue';
import MessageContainer from '@/Pages/Chat/MessageContainer.vue';
import InputMessage from '@/Pages/Chat/InputMessage.vue';
import ChatRoomSelection from '@/Pages/Chat/ChatRoomSelection.vue';
import TransmissionCard from '@/Pages/MyComponents/admin-transmission-card.vue';
import axios from 'axios';

export default {
    components: {
        AppLayout,
        MessageContainer,
        InputMessage,
        ChatRoomSelection,
        TransmissionCard
    },
    data: function ()  {
        return {
            messages: [],
            roomIdLink: window.location.href.split('/').pop(),
            room: null
        }
    },
    methods: {
        getRoom() {
            axios.get('/chat/room/' + this.roomIdLink)
                .then(response => {
                    this.room = response.data
                })
        },
        getMessages() {
            axios.get('/chat/room/' + this.roomIdLink + '/messages')
                .then(response => {
                    this.messages = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
    },
    watch: {
        room() {
            this.getMessages();
        },
    },
    created() {
        this.getRoom()
        this.getMessages()

        const likesChannel = Echo.channel('like.' + this.roomIdLink);
        likesChannel.listen('.like.new', (e) => {
            this.getMessages();
        });

        const chatChannel = Echo.channel('chat.' + this.roomIdLink);
        chatChannel.listen('.message.new', (e) => {
            this.getMessages();
        });

        const liveChannel = Echo.channel('live.status');
        liveChannel.listen('.live.status.new', (e) => {
            this.getRoom();
        });

    },
};
</script>

<template>
    <AppLayout :title="room ? `On en parle | Questions ${room.name}` : 'On en parle'">

        <div v-if="room" class="chat-wrapper">
            <div class="chat-transmission">
                <div class="transmission-card-container">
                    <div class="on-air card " v-if="room.on_air" >
                        <transmission-card :room="room"/>
                    </div>
                    <div class="card " v-else >
                        <transmission-card :room="room"/>
                    </div>
                </div>
            </div>

            <div class="chat-container">
                <message-container :messages="messages"/>
            </div>
        </div>
    </AppLayout>
</template>
