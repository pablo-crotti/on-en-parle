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
       async getMessages() {
            await axios.get(`/emission/${this.no}/status/${this.id}`)
                .then(response => {
                    this.messages = response.data;
                    this.messages = this.filterMessages(10);
                })
                .catch(error => {
                    console.log(error);
                });
        },

         async desarchiver(message){  
            message.status = 0;
        await axios.post(`/AdminInbox/message/${message.id}/update`, { status: message.status });
        this.messages = this.messages.filter(m => m.id !== message.id);


        },
        filterMessages(status) {
            console.log("le status est " + status);
            console.log(this.messages);

        return this.messages[10].filter(message => message.status === status);
        }

       
        
    },
    created() {
        this.getMessages();

        const chatChannel = Echo.channel("chat." + this.no);
        chatChannel.listen(".message.new", (e) => {
            this.getMessages();
        });

        const likesChannel = Echo.channel("like." + this.no);
        likesChannel.listen(".like.new", (e) => {
            this.getMessages();
        });
    }
};
</script>

<template>
        <div class="admin-archives-list">
        
            <div class="admin-messages-item" v-for="(content, index) in messages" :key="index">
                <AdminMessage :message="content"
                @archive="desarchiver"/>/></div>
        </div>
</template>