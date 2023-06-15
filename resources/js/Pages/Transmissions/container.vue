<script>
import AppLayout from '@/Layouts/AppLayoutUser.vue';
import axios from 'axios';
import TransmissionCard from '@/Pages/MyComponents/transmission-card.vue';

export default {
    components: {
        AppLayout,
        TransmissionCard
    },
    data: function ()  {                
        return {
            chatRooms: []
        }
    },
    methods: {
        getRooms() {
            axios.get('/chat/rooms')
                .then(response => {
                    this.chatRooms = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        } 
    },
    created() {
        this.getRooms()

        const liveChannel = Echo.channel('live.status');
        liveChannel.listen('.live.status.new', (e) => {
            this.getRooms();
        });

        const chatChannel = Echo.channel("rooms.update");
        chatChannel.listen(".event.on.rooms", (e) => {
            this.getRooms();
        });
    },
};
</script>

<template>
    <AppLayout title="On en parle | Émissions">
    <div class="transmissions-wrapper">
        <div class="transmission-card-container" v-for="(chatRoom, index) in chatRooms" :key="index">
            <a :href="'questions/' + chatRoom.id" v-if="chatRoom.on_air" class="on-air">
                <transmission-card :room="chatRoom" />
            </a>

            <a :href="'questions/' + chatRoom.id" v-else>
                <transmission-card :room="chatRoom" />
            </a>

        </div>
    </div>
    </AppLayout>
</template>
