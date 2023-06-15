<template>
    <div class="admin-messages-container">
        <div class="admin-messages-title-container">
            <div class="admin-messages-title"></div>
        </div>
        <div class="admin-messages-list">
            <div class="admin-messages-item">
                <chat-message> </chat-message>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";

export default {
    props: ["no", "id"],
    data() {
        return {
            status: [
                "Inbox",
                "Présélectionnés",
                "Sélectionnés",
                "Régie",
                "Prêt à diffuser",
            ],
        };
    },
    components: {
        ChatMessage,
    },
    setup(props) {
        const no = props.no;
        const id = props.id;

        return {
            no,
            id,
        };
    },

    methods: {
        getMessages() {
            axios
                .get(`/emission/${this.no}/status/${this.id}`)
                .then((response) => {
                    this.messages = response.data;
                    console.log(this.messages);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        this.getMessages();
    },
};
</script>
