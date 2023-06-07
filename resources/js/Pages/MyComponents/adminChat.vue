<template>
    <div class="admin-chat-container">
        <div class="admin-messages-container">
            <span v-for="message in messages">
                <adminMessageItem :message="message" :user="user" />
            </span>
            
        </div>
        <div class="admin-message-input-container">
            <adminMessageInput :room="room" :user="user" />
        </div>
    </div>
</template>
<script>
    import adminMessageInput from "@/Pages/MyComponents/adminMessageInput.vue";
    import adminMessageItem from "@/Pages/MyComponents/adminMessageItem.vue";

    export default {
        components: {
            adminMessageInput,
            adminMessageItem
        },
        props: ['room', 'user'],
        data: function () {
            return {
                messages: [],
            }
        },
        methods: {
            getAdminMessages() {
                axios
                    .get("/chat/room/" + this.room + "/admin-messages")
                    .then((response) => {
                        if (response.status == 200) {
                            this.messages = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        },
        created() {
            this.getAdminMessages();

            const adminMessages = Echo.channel("chat.admin." + this.room);
                adminMessages.listen(".admin.message.new", (e) => {
                this.getAdminMessages();
                this.$emit("adminMessageReceived");
            });
        },
    }
</script>