<template>
    <AppLayout title="On en parle | Réception (Inbox)">
        <adminChatContainer
            v-if="chatroomId"
            :room="chatroomId"
            :user="userId"
        />
        <div class="containerInbox">
            <div id="boutonsmangament">
                <div id="boutonsmangamenttype">
                    <dropdownFilter
                        :categories="categories"
                        @filter-applied="handleFilterApplied"
                    ></dropdownFilter>
                </div>
            </div>

            <div class="columns">
                <div class="column" v-for="status in [0, 1]" :key="status">
                    <div
                        class="admin-messages-container"
                        style="width: 30vw; margin-right: 30px"
                    >
                        <div class="admin-messages-title-container">
                            <div class="admin-messages-title" :style="{ backgroundColor: couleurtitre[status] }">
                                {{ statu[status] }}
                            </div>
                        </div>
                        <div
                            class="admin-messages-list"
                            :id="`column-${status}`"
                            @drop="drop($event, status)"
                            @dragover.prevent
                        >
                            <div
                                class="admin-messages-item"
                                v-for="message in sortedMessages.filter(
                                    (m) => m.status === status
                                )"
                            >
                                <chat-message
                                    :key="message.id"
                                    :message="message"
                                    :audiofiles="audiofiles"
                                    @dragstart="drag($event, message.id)"
                                    @modify="modifier"
                                    @delete="deleted"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: ChatMessages
 * Description: Displays chat messages in the admin section.
 * 
 * Component: AppLayoutAdmin
 * Description: Layout component for the admin section.
 * 
 * Component: DropdownFilter
 * Description: A dropdown component for filtering messages in the admin section.
 * 
 * Component: AdminChatContainer
 * Description: Container component for the admin chat functionality.
 * 
 * Component: archiveList
 * Description: Component for displaying the list of archived messages.
 * 
 * Component: ChatMessage
 * Description: Component for rendering a single chat message.
 * 
 * Component: adminChatContainer
 * Description: Container component for the admin chat functionality.
 */

import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import DropdownFilter from "@/Pages/MyComponents/dropdownFilter.vue";
import AdminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";

export default {
    components: {
        ChatMessage,
        AppLayout,
        DropdownFilter,
        AdminChatContainer,
    },

    props: ["userId"],

    data() {
        return {
            filteredMessages: [],
            statu: [
                "Inbox",
                "Présélectionnés",
                "Sélectionnés",
                "Régie",
                "Prêt à diffuser",
            ],
            sortType: "creation",
            categories: [
                {
                    name: "Date de création",
                    value: "creation",
                },
                {
                    name: "Nombre de likes",
                    value: "likes",
                },
            ],
            couleurtitre: [
                "#000000",
                "rgb(179, 23, 28,0.18)",
            ],
            messages: [],
            audiofiles: [],
            chatroomId: null,
        };
    },

    methods: {
        /**
         * Handles the filter applied event and updates the sort type.
         * @param {string} filterData - The filter data representing the sort type.
         */
        handleFilterApplied(filterData) {
            this.sortType = filterData;
        },

        /**
         * Handles the drag event and sets the message ID to the data transfer.
         * @param {Event} event - The drag event.
         * @param {number} messageId - The ID of the message being dragged.
         */
        drag(event, messageId) {
            event.dataTransfer.setData("text", messageId);
        },

        /**
         * Handles the drop event and updates the status of the dropped message.
         * @param {Event} event - The drop event.
         * @param {string} status - The status to update the message to.
         */
        async drop(event, status) {
            const messageId = parseInt(event.dataTransfer.getData("text"));
            const message = this.messages.find((m) => m.id === messageId);

            if (message) {
                message.status = status;
                await axios.post(`/AdminInbox/message/${message.id}/update`, {
                    status,
                });
            }
        },

        /**
         * Updates the content of a message.
         * @param {object} message - The message object to update.
         */
        async modifier(message) {
            await axios.post(`/AdminInbox/message/${message.id}/content`, {
                content: message.content,
            });
        },

        /**
         * Deletes a message.
         * @param {object} message - The message object to delete.
         */
        async deleteMessage(message) {
            try {
                const response = await axios.post(
                    `/AdminInbox/message/${message.id}/delete`
                );
                this.messages = this.messages.filter(
                    (m) => m.id !== message.id
                );
            } catch (error) {
                console.error(error);
            }
        },

        /**
         * Sets the status of a message to "deleted".
         * @param {object} message - The message object to mark as deleted.
         */
        async deleted(message) {
            let status = 10;
            try {
                await axios.post(`/AdminInbox/message/${message.id}/update`, {
                    status,
                });
                this.messages = this.messages.filter(
                    (m) => m.id !== message.id
                );
            } catch (error) {
                console.error(error);
            }
        },

        /**
         * Filters the messages based on the audio files.
         */
        filterMessages() {
            this.filteredMessages = this.messages.filter((message) => {
                return !this.audiofiles.some(
                    (audiofile) => audiofile.id === message.id
                );
            });
        },

        /**
         * Sorts the messages by creation date.
         */
        sortByCreation() {
            console.log("Création");
            this.messages.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },

        /**
         * Sorts the messages by number of likes.
         */
        sortByLikes() {
            this.messages.sort((a, b) => b.nb_likes - a.nb_likes);
        },

        /**
         * Fetches the messages for the chat room.
         */
        getMessages() {
            const currentUrl = window.location.pathname;
            const idFromUrl = currentUrl.substring(
                currentUrl.lastIndexOf("/") + 1
            );

            this.chatroomId = idFromUrl;

            axios
                .get(`/admin/reception/api/animator/${idFromUrl}`)
                .then((response) => {
                    this.messages = response.data.initialMessages;
                    this.audiofiles = response.data.audioChatroom;
                    this.callChatroom = response.data.callChatroom;
                    this.idroom = response.data.idroom;
                });
        },
    },

    computed: {
        /**
         * Sorts the messages by creation date.
         * @returns {Array} - The sorted messages by creation date.
         */
        sortedByCreation() {
            return [...this.messages].sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },

        /**
         * Sorts the messages by number of likes.
         * @returns {Array} - The sorted messages by number of likes.
         */
        sortedByLikes() {
            return [...this.messages].sort((a, b) => b.nb_likes - a.nb_likes);
        },

        /**
         * Returns the sorted messages based on the current sort type.
         * @returns {Array} - The sorted messages.
         */
        sortedMessages() {
            if (this.sortType === "creation") {
                return this.sortedByCreation;
            } else if (this.sortType === "likes") {
                return this.sortedByLikes;
            }
        },
    },

    created() {
        this.getMessages();

        const chatChannel = Echo.channel("chat." + this.chatroomId);
        chatChannel.listen(".message.new", (e) => {
            this.getMessages();
        });

        const likesChannel = Echo.channel("like." + this.chatroomId);
        likesChannel.listen(".like.new", (e) => {
            this.getMessages();
        });
    },
};
</script>
