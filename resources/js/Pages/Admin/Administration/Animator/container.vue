<template>
    <AppLayout title="On en parle | Administration (Animateur)">
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
                <div
                    class="column"
                    v-for="status in [0, 1, 2, 3]"
                    :key="status"
                >
                    <div class="admin-messages-container">
                        <div class="admin-messages-title-container">
                            <div class="admin-messages-title">
                                {{
                                    [
                                        "Messages Vocaux",
                                        "Appels en attente",
                                        "Messages",
                                        "Diffusé",
                                    ][status]
                                }}
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
                                v-for="message in [
                                    audioMessages,
                                    callMessages,
                                    textMessages,
                                    statusTenMessages,
                                ][status]"
                            >
                                <chat-message
                                    :key="message.id"
                                    :message="message"
                                    :calls="calls"
                                    isInAnimator
                                    :data-message-id="message.id"
                                    @cacher="hideMessage"
                                    :audiofiles="audiofiles"
                                    @dragstart="drag($event, message.id)"
                                    @modify="modifier"
                                    @archive="deleteMessage"
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
 * Component for managing chat messages in the admin interface.
 *
 * @component
 * @example
 * <admin-chat-container
 *     :initialMessages="messages"
 *     :audioChatroom="audiofiles"
 *     :idroom="idroom"
 *     :callChatroom="calls"
 *     :userId="userId"
 * ></admin-chat-container>
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
         * Initial chat messages data.
         */
        initialMessages: {
            type: Array,
            required: true,
        },
        /**
         * Audio chat room data.
         */
        audioChatroom: {
            type: Array,
            required: true,
        },
        /**
         * Chat room ID.
         */
        idroom: {
            required: true,
        },
        /**
         * Call chat room data.
         */
        callChatroom: {
            type: Array,
            required: true,
        },
        /**
         * User ID.
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
            chatroomId: null,
            calls: this.callChatroom,
            draggedElement: null,

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
         * Event handler for when a filter is applied.
         *
         * @param {Object} filterData - The filter data.
         */
        handleFilterApplied(filterData) {
            this.sortType = filterData;
        },

        /**
         * Event handler for the drag event.
         *
         * @param {Event} event - The drag event.
         * @param {number} messageId - The ID of the dragged message.
         */
        drag(event, messageId) {
            event.dataTransfer.setData("text", messageId);
        },

        /**
         * Event handler for the drop event.
         *
         * @param {Event} event - The drop event.
         * @param {number} status - The status to update the message to.
         */
        async drop(event, status) {
            const messageId = parseInt(event.dataTransfer.getData("text"));
            const message = this.messages.find((m) => m.id === messageId);

            if (message) {
                if ([0, 1, 2].includes(status)) {
                    message.status = 5;
                } else if (status === 3) {
                    message.status = 10;
                }
                await axios.post(`/AdminInbox/message/${message.id}/update`, {
                    status: message.status,
                });
            }
        },

        /**
         * Updates the content of a message.
         *
         * @param {Object} message - The message object.
         */
        async modifier(message) {
            await axios.post(`/AdminInbox/message/${message.id}/content`, {
                content: message.content,
            });
        },

        /**
         * Deletes a message.
         *
         * @param {Object} message - The message object.
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
         * Filters the messages to exclude audio messages.
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
         * Hides a message.
         *
         * @param {Object} message - The message object.
         */
        hideMessage(message) {
            this.messages = this.messages.filter((m) => m.id !== message.id);
        },

        /**
         * Retrieves the chat messages from the server.
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
        affichedon() {},
        /**
         * Retrieves audio messages.
         *
         * @returns {Array} - The array of audio messages.
         */
        audioMessages() {
            return this.messages.filter(
                (m) =>
                    m.audio &&
                    m.audio.length > 0 &&
                    m.status === 5 &&
                    m.call.length === 0
            );
        },

        /**
         * Retrieves call messages.
         *
         * @returns {Array} - The array of call messages.
         */
        callMessages() {
            return this.messages.filter(
                (m) => m.call && m.call.length > 0 && m.status === 5
            );
        },

        /**
         * Retrieves text messages.
         *
         * @returns {Array} - The array of text messages.
         */
        textMessages() {
            return this.messages.filter(
                (m) =>
                    (!m.audio || m.audio.length === 0) &&
                    (!m.call || m.call.length === 0) &&
                    m.status === 5
            );
        },

        /**
         * Retrieves messages with status 10.
         *
         * @returns {Array} - The array of messages with status 10.
         */
        statusTenMessages() {
            return this.messages.filter((m) => m.status === 10);
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
        } else {
            console.log("pas d'id recu");
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
