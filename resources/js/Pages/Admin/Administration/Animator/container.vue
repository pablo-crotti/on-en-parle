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
import axios from "axios";
import ChatMessage from "@/Pages/MyComponents/ChatMessages.vue";
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import dropdownFilter from "@/Pages/MyComponents/dropdownFilter.vue";
import adminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";

export default {
    components: {
        ChatMessage,
        AppLayout,
        dropdownFilter,
        adminChatContainer,
    },

    props: {
        initialMessages: {
            type: Array,
            required: true,
        },
        audioChatroom: {
            type: Array,
            required: true,
        },
        idroom: {
            required: true,
        },
        callChatroom: {
            type: Array,
            required: true,
        },
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
        handleFilterApplied(filterData) {
            this.sortType = filterData;
        },

        drag(event, messageId) {
            event.dataTransfer.setData("text", messageId);
        },

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
        async modifier(message) {
            await axios.post(`/AdminInbox/message/${message.id}/content`, {
                content: message.content,
            });
        },
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
        filterMessages() {
            this.filteredMessages = this.messages.filter((message) => {
                return !this.audiofiles.some(
                    (audiofile) => audiofile.id === message.id
                );
            });
        },
        sortByCreation() {
            this.messages.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });
        },
        sortByLikes() {
            this.messages.sort((a, b) => b.nb_likes - a.nb_likes);
        },

        hideMessage(message) {
            this.messages = this.messages.filter((m) => m.id !== message.id);
        },
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
        audioMessages() {
            return this.messages.filter(
                (m) =>
                    m.audio &&
                    m.audio.length > 0 &&
                    m.status === 5 &&
                    m.call.length === 0
            );
        },
        callMessages() {
            return this.messages.filter(
                (m) => m.call && m.call.length > 0 && m.status === 5
            );
        },
        textMessages() {
            return this.messages.filter(
                (m) =>
                    (!m.audio || m.audio.length === 0) &&
                    (!m.call || m.call.length === 0) &&
                    m.status === 5
            );
        },
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
