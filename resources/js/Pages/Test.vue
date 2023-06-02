<script>
    import AppLayout from '@/Layouts/AppLayoutUser.vue';
    import AdminMessage from '@/Pages/MyComponents/admin-message.vue';
    import axios from 'axios';
    
    export default {
        components: {
            AppLayout,
            AdminMessage
        },
        setup() {
            const request = [{"id":2,"content":"yessir","created_at":"2023-05-26T12:51:28.000000Z","updated_at":"2023-05-30T08:17:56.000000Z","nb_likes":0,"status":5,"chat_room_id":1,"text":[{"id":2,"chat_message_id":2}],"audio":[],"call":[]},{"id":4,"content":"l'utilisateur se demande si on peut mettre de l'eau dans les céréales","created_at":"2023-05-26T12:51:28.000000Z","updated_at":"2023-05-30T08:17:56.000000Z","nb_likes":32,"status":5,"chat_room_id":1,"text":[],"audio":[{"id":2,"audio_file":"storage\\audio\\what-the-hell_H0K7ORA.mp3","chat_message_id":4}],"call":[]},{"id":1,"content":"dasda","created_at":"2023-05-26T12:51:22.000000Z","updated_at":"2023-05-26T12:51:24.000000Z","nb_likes":1,"status":10,"chat_room_id":1,"text":[{"id":1,"chat_message_id":1}],"audio":[],"call":[]},{"id":6,"content":"L'utilisateur demande si le lait est bon","created_at":null,"updated_at":null,"nb_likes":0,"status":10,"chat_room_id":1,"text":[],"audio":[],"call":[{"id":1,"caller":"Mike Hawk","chat_message_id":6}]},{"id":2,"content":"yessir","created_at":"2023-05-26T12:51:28.000000Z","updated_at":"2023-05-30T08:17:56.000000Z","nb_likes":0,"status":5,"chat_room_id":1,"text":[{"id":2,"chat_message_id":2}],"audio":[],"call":[]},{"id":4,"content":"null null null null null null null nullnull nullnullnullnullnull","created_at":"2023-05-26T12:51:28.000000Z","updated_at":"2023-05-30T08:17:56.000000Z","nb_likes":32,"status":3,"chat_room_id":1,"text":[],"audio":[{"id":2,"audio_file":"storage\\audio\\what-the-hell_H0K7ORA.mp3","chat_message_id":4}],"call":[]},{"id":1,"content":"dasda","created_at":"2023-05-26T12:51:22.000000Z","updated_at":"2023-05-26T12:51:24.000000Z","nb_likes":1,"status":2,"chat_room_id":1,"text":[{"id":1,"chat_message_id":1}],"audio":[],"call":[]},{"id":6,"content":null,"created_at":null,"updated_at":null,"nb_likes":0,"status":1,"chat_room_id":1,"text":[],"audio":[],"call":[{"id":1,"caller":"Mike Hawk","chat_message_id":6}]}]




            return {
                request
            }

        }
    }
</script>

<template>
    <AppLayout title="test">
    <div class="container">
        <div v-for="message in request" :key="message.id">
        <AdminMessage :message="message" />
    </div>
</div> 
    <!-- fait une boucle qui parcours la request -->

    </AppLayout>

</template>

<style>
    .container {
        max-width: 30%;
    }
</style>