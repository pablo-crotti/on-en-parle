<template>
    <div class="admin-archives-list">
        <div
            class="admin-messages-item"
            v-for="(content, index) in messages"
            :key="index"
        >
            <AdminMessage :message="content" @archive="desarchiver" />/>
        </div>
    </div>
</template>

<script>
/**
 * Component: AdminMessage
 * Description: A custom component for displaying an admin message.
 *              It is used within the parent component to render each admin message.
 */
import axios from "axios";
import AdminMessage from "@/Pages/MyComponents/admin-message.vue";

export default {
    props: ["no", "id"],
    data() {
        return {
            messages: [],
        };
    },
    components: {
        AdminMessage,
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
        /**
         * Fetches the messages for the specified emission and status.
         */
        async getMessages() {
            await axios
                .get(`/emission/${this.no}/status/${this.id}`)
                .then((response) => {
                    this.messages = response.data;
                    this.messages = this.filterMessages(10);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        /**
         * Updates the status of a message to mark it as unarchived.
         * @param {object} message - The message object to update.
         */
        async desarchiver(message) {
            message.status = 0;
            await axios.post(`/AdminInbox/message/${message.id}/update`, {
                status: message.status,
            });
            this.messages = this.messages.filter((m) => m.id !== message.id);
        },

        /**
         * Filters the messages based on the specified status.
         * @param {number} status - The status to filter the messages.
         * @returns {array} - The filtered messages.
         */
        filterMessages(status) {
            console.log("le status est " + status);
            console.log(this.messages);

            return this.messages[10].filter(
                (message) => message.status === status
            );
        },
    },
    created() {
        // Fetch the initial messages on component creation
        this.getMessages();

        // Listen for new chat messages in the emission
        const chatChannel = Echo.channel("chat." + this.no);
        chatChannel.listen(".message.new", (e) => {
            this.getMessages();
        });

        // Listen for new likes in the emission
        const likesChannel = Echo.channel("like." + this.no);
        likesChannel.listen(".like.new", (e) => {
            this.getMessages();
        });
    },
};
</script>
