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
                })
                .catch(error => {
                    console.log(error);
                });
        },
         async desarchiver(message){  
            console.log("le message id ");
            console.log("le message id " + message.id);
        await axios.post(`/AdminInbox/message/${message.id}/update`, { status: 1 });
        console.log("le message id " + message);

        },
       
        
    },
    created() {
        this.getMessages();
    }
};
</script>

<template>
        <div class="admin-archives-list">
            <div class="admin-messages-item" v-for="(content, index) in messages[5]" :key="index">
                <AdminMessage 
                :message="content"
                @archive="test"/>
            </div>
            <div class="admin-messages-item" v-for="(content, index) in messages[10]" :key="index">
                <AdminMessage :message="content"
                @archive="desarchiver"/>/></div>
        </div>
</template>