<script>
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import adminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";
import axios from "axios";

export default {
    components: {
        AppLayout,
        adminChatContainer
    },
    props: ['userId'],
    data: function () {
        return {
            roomIdLink: window.location.href.split("/").pop(),
            
        };
    },
    methods: {

        // getAdminMessages() {
        //     axios.get("/chat/room/" + this.roomIdLink + "/admin-messages").then((response) => {
        //         this.adminMessages = response.data;
        //     });
        // }
        
        // getMessages() {
        //     axios
        //         .get("/chat/room/" + this.roomIdLink + "/messages")
        //         .then((response) => {
        //             this.messages = response.data;
        //         })
        //         .catch((error) => {
        //             console.log(error);
        //         });
        // },
    },
    mounted() {
        
    },
    created() {
        // this.getAdminMessages();
        // this.getRoom();
    },
};
</script>

<template>
    <AppLayout
        :title="'Test'">
    <!-- <p v-if="adminMessages">{{ adminMessages }}</p> -->
        <adminChatContainer v-if="roomIdLink" :room="roomIdLink" :user="userId"/>
    </AppLayout>
</template>
