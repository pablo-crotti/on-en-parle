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
/**
 * Component: adminMessageInput
 * Description: Represents an input field for admins to enter their messages in a chat room.
 * 
 * Component: adminMessageItem
 * Description: Represents an individual admin message in a chat room.
 */

import adminMessageInput from "@/Pages/MyComponents/adminMessageInput.vue";
import adminMessageItem from "@/Pages/MyComponents/adminMessageItem.vue";
import axios from "axios";

export default {
  components: {
    adminMessageInput,
    adminMessageItem,
  },
  props: {
    room: {
      type: String,
      required: true,
    },
    user: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      messages: [],
    };
  },
  methods: {
    /**
     * Method: getAdminMessages
     * Description: Retrieves the admin messages for the chat room from the server.
     */
    getAdminMessages() {
      axios
        .get(`/chat/room/${this.room}/admin-messages`)
        .then((response) => {
          if (response.status === 200) {
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

    const adminMessagesChannel = Echo.channel(`chat.admin.${this.room}`);
    adminMessagesChannel.listen(".admin.message.new", (e) => {
      this.getAdminMessages();
      this.$emit("adminMessageReceived");
    });
  },
};

</script>
