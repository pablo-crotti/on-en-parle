<template>
    <AppLayout title="On en parle | Administration (Régie)">
        <adminChatContainer
            v-if="chatroomId"
            :room="chatroomId"
            :user="userId"
        />
        <div class="containerManagement">
            <div id="boutonsmangament">
                <div id="boutonsmangamenttype">
                    <dropdownFilter
                        :categories="categories"
                        @filter-applied="handleFilterApplied"
                    ></dropdownFilter>
                </div>
            </div>
            <div class="columns">
                <div class="column" v-for="status in [0, 5]" :key="status">
                    <div class="admin-messages-container">
                        <div
                            class="admin-messages-title-container"
                            :style="{ backgroundColor: couleurtitre[status] }"
                        >
                            <div class="admin-messages-title">
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
                                v-if="status === 0"
                                v-for="message in sortedAudioMessages"
                            >
                                <chat-message
                                    :key="message.id"
                                    :message="message"
                                    :calls="calls"
                                    :audiofiles="audiofiles"
                                    @dragstart="drag($event, message.id)"
                                    @modify="modifier"
                                    @delete="deleteMessage"
                                />
                            </div>
                            <div
                                class="admin-messages-item"
                                v-if="status === 5"
                                v-for="message in statusFiveMessages"
                            >
                                <chat-message
                                    :key="message.id"
                                    :message="message"
                                    :calls="calls"
                                    :audiofiles="audiofiles"
                                    @dragstart="drag($event, message.id)"
                                    @modify="modifier"
                                    @delete="deleteMessage"
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
 * Component: ChatMessage
 * Description: A component for displaying chat messages.
 * 
 * Component: AppLayoutAdmin
 * Description: The main layout component for the admin section.
 * 
 * Component: dropdownFilter
 * Description: A dropdown component for filtering data.
 *
 * Component: adminChatContainer
 * Description: A container component for the admin chat interface.
 */
import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import dropdownFilter from "@/Pages/MyComponents/dropdownFilter.vue";
import adminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";

/**
 * Component for handling admin chat functionality.
 */
export default {
    components: {
        ChatMessage,
        AppLayout,
        dropdownFilter,
        adminChatContainer,
    },

    props: {
        /**
         * The user ID.
         */
        userId: {
            required: true,
        },
    },

    data() {
        return {
            messages: [],
            audiofiles: [],
            callChatroom: [],
            idroom: null,
            filteredMessages: [],
            statu: ["Message Audio", "", "", "", "", "A diffuser"],
            chatroomId: null,
            calls: this.callChatroom,
            sortType: "creation",
            couleurtitre: [
                "rgb(179, 23, 28)",
                "red",
                "",
                "#FFFF00",
                "#008000",
                "#2216C2",
            ],
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
        };
    },

    methods: {
        /**
         * Handles the applied filter for sorting messages.
         * @param {string} filterData - The filter value.
         */
        handleFilterApplied(filterData) {
            this.sortType = filterData;
        },

        /**
         * Handles the drag event for message.
         * @param {Event} event - The drag event.
         * @param {number} messageId - The ID of the dragged message.
         */
        drag(event, messageId) {
            event.dataTransfer.setData("text", messageId);
        },

        /**
         * Handles the drop event for message.
         * @param {Event} event - The drop event.
         * @param {number} status - The status of the message.
         */
        async drop(event, status) {
            const messageId = parseInt(event.dataTransfer.getData("text"));
            const message = this.messages.find((m) => m.id === messageId);

            if (message) {
                if (message.audio && message.status === 5 && status === 0) {
                    message.status = 3;
                } else {
                    message.status = status;
                }
                await axios.post(`/AdminInbox/message/${message.id}/update`, {
                    status: message.status,
                });
            }
        },

        /**
         * Modifies the content of a message.
         * @param {object} message - The message object.
         */
        async modifier(message) {
            await axios.post(`/AdminInbox/message/${message.id}/content`, {
                content: message.content,
            });
        },

        /**
         * Deletes a message.
         * @param {object} message - The message object.
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
         * Filters the messages based on audio files.
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
         * Retrieves the messages from the server.
         */
        getMessages() {
            const currentUrl = window.location.pathname;
            const idFromUrl = currentUrl.substring(
                currentUrl.lastIndexOf("/") + 1
            );

            this.chatroomId = idFromUrl;

            axios
                .get(`/admin/reception/api/control/${idFromUrl}`)
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
         * Retrieves the audio messages.
         * @returns {Array} - The audio messages.
         */
        audioMessages() {
            return this.messages.filter(
                (m) => m.audio && m.audio.length > 0 && m.status === 3
            );
        },

        /**
         * Retrieves the messages with status five.
         * @returns {Array} - The messages with status five.
         */
        statusFiveMessages() {
            return this.messages.filter(
                (m) => m.status === 5 && m.audio.length > 0
            );
        },

        /**
         * Retrieves the call messages.
         * @returns {Array} - The call messages.
         */
        callMessages() {
            return this.messages.filter(
                (m) => m.call && m.call.length > 0 && m.status === 3
            );
        },

        /**
         * Retrieves the sorted audio messages based on the selected sort type.
         * @returns {Array} - The sorted audio messages.
         */
        sortedAudioMessages() {
            let messages = [...this.audioMessages];
            if (this.sortType === "creation") {
                messages.sort(
                    (a, b) => new Date(b.created_at) - new Date(a.created_at)
                );
            } else if (this.sortType === "likes") {
                messages.sort((a, b) => b.nb_likes - a.nb_likes);
            }
            return messages;
        },
    },

    async created() {
        this.getMessages();
        let convertedId = null;

        if (typeof this.idroom === "string") {
            const trimmedId = this.idroom.trim();
            if (trimmedId !== "") {
                convertedId = parseInt(trimmedId);
            }
        }

        if (convertedId !== null) {
            this.chatroomId = convertedId;
        }

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
