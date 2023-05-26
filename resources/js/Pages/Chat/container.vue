<script>
import AppLayout from '@/Layouts/AppLayoutUser.vue';
import MessageContainer from '@/Pages/Chat/MessageContainer.vue';
import InputMessage from '@/Pages/Chat/InputMessage.vue';
import ChatRoomSelection from '@/Pages/Chat/ChatRoomSelection.vue';
import axios from 'axios';

export default {
    components: {
        AppLayout,
        MessageContainer,
        InputMessage,
        ChatRoomSelection
    },
    data: function ()  {                
        return {
            messages: [],
            roomIdLink: window.location.href.split('/').pop(),
            room: {}
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
        }
    },
    created() {
        this.getRoom()
        this.getMessages()
    },
};
</script>

<template>
    <AppLayout :title="`On en parle | Questions ${room.name}`">

        <div class="chat-wrapper">
            <div class="chat-container">
                <message-container :messages="messages"/>
                <input-message 
                    :room="roomIdLink" 
                    v-on:messagesent="getMessages"/>
            </div>
        </div>
    </AppLayout>
</template>
