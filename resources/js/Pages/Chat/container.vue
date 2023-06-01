<script>
import AppLayout from '@/Layouts/AppLayoutUser.vue';
import MessageContainer from '@/Pages/Chat/MessageContainer.vue';
import InputMessage from '@/Pages/Chat/InputMessage.vue';
import ChatRoomSelection from '@/Pages/Chat/ChatRoomSelection.vue';
import TransmissionCard from '@/Pages/MyComponents/transmission-card.vue'; 
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
});


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

        echo.channel('chat.' + this.roomIdLink)
            .listen('.message.new', (event) => {
                // console.log('Nouveau message reçu:', event);
                // Mettez à jour l'affichage ou effectuez d'autres actions ici
                this.getMessages();
        });
    },
};
</script>

<template>
    <AppLayout :title="room ? `On en parle | Questions ${room.name}` : 'On en parle'">

        <div v-if="room" class="chat-wrapper">
            <div class="chat-transmission">
                <div class="transmission-card-container">
                    <div class="card">
                        <transmission-card :room="room"/>
                    </div>
                </div>
            </div>
            
            <div class="chat-container">
                <message-container :messages="messages"/>
                <input-message 
                    :room="roomIdLink" 
                    v-on:messagesent="getMessages"/>
            </div>
        </div>
    </AppLayout>
</template>
