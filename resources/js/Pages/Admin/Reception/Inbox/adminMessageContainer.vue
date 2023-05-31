<script>
    import axios from 'axios';
    import AdminMessage from '@/Pages/MyComponents/admin-message.vue';

    export default {
        props: ['no', 'id'],
        data() {
            return {
                messages: [],
                status: ["Inbox", "Présélectionnés", "Sélectionnés", "Régie", "Prêt à diffuser"]
            };
        },
        components: {
            AdminMessage
        },
        methods: {
            getMessages() {
                axios.get(`/emission/${this.no}/status/${this.id}`)
                    .then(response => {
                        this.messages = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        created() {
            this.getMessages();
        }
    };
</script>


<template>
    <div v-for="(message, index) in messages" :key="index" class="admin-messages-container">
        <div class="admin-messages-title-container">
            <div class="admin-messages-title">{{status[message[0].status]}}</div>
        </div>
        <div class="admin-messages-list">
            <div class="admin-messages-item" v-for="(content, index) in message" :key="index"><AdminMessage :message="content"/></div>
        </div>
    </div>
</template>