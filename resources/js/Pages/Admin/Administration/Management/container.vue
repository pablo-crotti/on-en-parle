<template>
    <AppLayout title="On en parle | Administration (Gestion)">
        <adminChatContainer
            v-if="chatroomId"
            :room="chatroomId"
            :user="userId"
        />
        <modalValidation
            :title="modalTitle"
            :message="modalMessage"
            :is-open="isModalOpen"
            @close="closeModal"
        />
        <div class="containerManagement">
            <div id="boutonsmangament">
                <dropdownFilter
                    :categories="categories"
                    @filter-applied="handleFilterApplied"
                ></dropdownFilter>
                <call-form v-if="chatroomId" :room="chatroomId"></call-form>
            </div>
            <div class="columns">
                <div
                    class="column"
                    v-for="status in [0, 1, 2, 3, 5]"
                    :key="status"
                >
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
                                v-for="message in sortedMessages.filter(
                                    (m) => m.status === status
                                )"
                            >
                                <chat-message
                                    :key="message.id"
                                    :message="message"
                                    :calls="calls"
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
 * Component: ChatMessage
 * Description: A component for displaying chat messages.
 *
 * Component: CallForm
 * Description: A component for displaying a form for making a call.
 *
 * Component: AppLayoutAdmin
 * Description: The main layout component for the admin section.
 *
 * Component: modalValidation
 * Description: A modal component for validating user inputs.
 *
 * Component: dropdownFilter
 * Description: A dropdown component for filtering data.
 *
 * Component: adminChatContainer
 * Description: A container component for the admin chat interface.
 */

import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";
import CallForm from "@/Pages/MyComponents/CallForm.vue";
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import modalValidation from "@/Pages/MyComponents/modalValidation.vue";
import dropdownFilter from "@/Pages/MyComponents/dropdownFilter.vue";
import adminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";

export default {
    components: {
        CallForm,
        ChatMessage,
        AppLayout,
        modalValidation,
        dropdownFilter,
        adminChatContainer,
    },
    props: ["userId"],

    data() {
        return {
            messages: [],
            audiofiles: [],
            callChatroom: [],
            idroom: null,
            filteredMessages: [],
            statu: [
                "Inbox",
                "Présélectionnés",
                "Sélectionnés",
                "Régie",
                "",
                "Prêt à diffuser",
            ],
            couleurtitre: [
                "#000000",
                "rgb(179, 23, 28,0.18)",
                "rgb(179, 23, 28,0.45)",
                "rgb(179, 23, 28,0.7)",
                "",
                "rgb(179, 23, 28)",
            ],
            chatroomId: null,
            calls: this.callChatroom,
            sortType: "creation",
            isModalOpen: false,
            modalTitle: "Erreur",
            modalMessage: "",
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
        drop(event, status) {
            const messageId = parseInt(event.dataTransfer.getData("text"));
            const message = this.messages.find((m) => m.id === messageId);

            if (message) {
                if (status === 3 && (!message.audio[0] || message.call[0])) {
                    event.preventDefault();
                    this.modalMessage =
                        "Vous ne pouvez pas mettre ce message en régie";
                    this.openModal();
                } else if (
                    status === 5 &&
                    message.audio[0] &&
                    !message.call[0]
                ) {
                    event.preventDefault();
                    this.modalMessage =
                        "Vous ne pouvez pas mettre ce message en prêt à diffuser";
                    this.openModal();
                } else {
                    axios
                        .post(`/AdminInbox/message/${message.id}/update`, {
                            status,
                        })
                        .then((response) => {
                            if (response.status == 200) {
                                this.getMessages();
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
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
         * Sets a message as deleted.
         * @param {object} message - The message object.
         */
        async deleted(message) {
            try {
                await axios.post(`/AdminInbox/message/${message.id}/update`, {
                    status: 10,
                });
            } catch (error) {
                console.error(error);
            }
        },

        /**
         * Filters the messages to exclude audio files.
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
         * Opens the modal.
         */
        openModal() {
            this.isModalOpen = true;
        },

        /**
         * Closes the modal.
         */
        closeModal() {
            this.isModalOpen = false;
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
                .get(`/admin/reception/api/management/${idFromUrl}`)
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
         * Returns the messages sorted by creation date.
         * @returns {Array} - The sorted messages.
         */
        sortedByCreation() {
            return [...this.messages].sort(
                (a, b) => new Date(b.created_at) - new Date(a.created_at)
            );
        },

        /**
         * Returns the messages sorted by number of likes.
         * @returns {Array} - The sorted messages.
         */
        sortedByLikes() {
            return [...this.messages].sort((a, b) => b.nb_likes - a.nb_likes);
        },

        /**
         * Returns the sorted messages based on the selected sort type.
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
