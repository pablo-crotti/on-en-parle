<script>
    import axios from 'axios';
    import AdminMessage from './admin-message.vue';

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
        setup(props) {
            const no = props.no;
            const id = props.id;

            return {
                no,
                id
            };
        },
        methods: {
            getMessages() {
                axios.get(`/emission/${this.no}/status/${this.id}`)
                    .then(response => {
                        this.messages = response.data;
                        console.log(this.messages);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
        },
        created() {
            this.getMessages();
        }
    };
</script>


<template>
    <div class="admin-messages-container">
        <div class="admin-messages-title-container">
            <div class="admin-messages-title">{{status[id]}}</div>
        </div>
        <div class="admin-messages-list">
            <div class="admin-messages-item" v-for="(content, index) in messages[id]" :key="index"><AdminMessage :message="content"/></div>
        </div>
    </div>
</template>