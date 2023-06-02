<script>
import axios from 'axios';
import AdminMessage from '@/Pages/MyComponents/admin-message.vue';

export default {
    props: ['no', 'id'],
    data() {
        return {
            messages: []
            
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
        }
    },
    created() {
        this.getMessages();
    }
};
</script>

<template>
        <div class="admin-archives-list">
            <div class="admin-messages-item" v-for="(content, index) in messages[5]" :key="index"><AdminMessage :message="content"/></div>
            <div class="admin-messages-item" v-for="(content, index) in messages[10]" :key="index"><AdminMessage :message="content"/></div>
        </div>
</template>