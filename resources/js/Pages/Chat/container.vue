<template>
    <AppLayout
        :title="room ? `On en parle | Questions ${room.name}` : 'On en parle'"
    >
        <div v-if="room" class="chat-wrapper">
            <div class="chat-transmission">
                <div class="transmission-card-container">
                    <div class="on-air card" v-if="room.on_air">
                        <transmission-card :room="room" />
                    </div>
                    <div class="card" v-else>
                        <transmission-card :room="room" />
                    </div>
                </div>
            </div>

            <div
                class="transmission-player"
                v-if="room.audio_file && !room.on_air"
            >
                <button @click="playTransmission()">
                    <span class="material-symbols-outlined play-icon">
                        play_circle
                    </span>
                </button>
                <audio controls>
                    <source :src="room.audio_file" type="audio/mp3" />
                </audio>
            </div>

            <Player
                v-if="room.audio_file"
                :room="room"
                :status="playerStatus"
            />
            <div class="chat-container">
                <message-container :messages="messages" />
                <input-message
                    :room="roomIdLink"
                    v-on:messagesent="getMessages"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: The layout component for the application.
 * 
 * Component: MessageContainer
 * Description: The container component for displaying messages in a chat room.
 * 
 * Component: InputMessage
 * Description: The component for inputting a message in a chat room.
 *
 * Component: ChatRoomSelection
 * Description: The component for selecting a chat room.
 * 
 * Component: TransmissionCard
 * Description: The component for displaying transmission cards.
 * 
 * Component: Player
 * Description: The component for controlling audio player.
 */
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import MessageContainer from "@/Pages/Chat/messageContainer.vue";
import InputMessage from "@/Pages/Chat/inputMessage.vue";
import ChatRoomSelection from "@/Pages/Chat/chatRoomSelection.vue";
import TransmissionCard from "@/Pages/MyComponents/transmission-card.vue";
import Player from "@/Pages/Chat/player.vue";
import axios from "axios";

export default {
    components: {
        AppLayout,
        MessageContainer,
        InputMessage,
        ChatRoomSelection,
        TransmissionCard,
        Player,
    },
    data: function () {
        return {
            messages: [], // Holds the list of messages
            roomIdLink: null, // The ID of the current chat room extracted from the URL
            room: null, // The current chat room object
            playerStatus: false, // Tracks the status of the audio player (playing or paused)
        };
    },
    methods: {
        /**
         * Fetches the chat room details from the server based on the roomIdLink.
         */
        getRoom() {
            axios
                .get("/chat/room/" + this.roomIdLink)
                .then((response) => {
                    this.room = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        /**
         * Fetches the list of messages for the current chat room from the server.
         */
        getMessages() {
            axios
                .get("/chat/room/" + this.roomIdLink + "/textmessages")
                .then((response) => {
                    this.messages = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        /**
         * Toggles the play/pause status of the audio player and updates the player icon.
         */
        togglePlayPause() {
            const player = document.querySelector("audio");
            const playIcon = document.querySelector(".play-icon");
            const playIconSecond = document.querySelector(".play-icon-second");

            if (player.paused) {
                player.play();
                this.playerStatus = true;
                playIcon.innerHTML = playIconSecond.innerHTML = "pause";
            } else {
                player.pause();
                this.playerStatus = false;
                playIcon.innerHTML = playIconSecond.innerHTML = "play_circle";
            }
        },

        /**
         * Sets up the event listeners and functionality for playing the transmission.
         */
        playTransmission() {
            const player = document.querySelector("audio");
            const progressBar = document.querySelector(".player-progress-bar");
            const playIconSecond = document.querySelector(".play-icon-second");
            const back = document.querySelector(".back");
            const forward = document.querySelector(".forward");

            player.addEventListener("error", (event) => {
                this.togglePlayPause();
            });

            this.togglePlayPause();

            player.addEventListener("timeupdate", function () {
                progressBar.max = player.duration;
                progressBar.value = player.currentTime;
            });

            progressBar.addEventListener("change", function () {
                player.currentTime = progressBar.value;
            });

            playIconSecond.addEventListener("click", () => {
                this.togglePlayPause();
            });

            back.addEventListener("click", () => {
                player.currentTime -= 10;
            });

            forward.addEventListener("click", () => {
                player.currentTime += 10;
            });
        },
    },
    created() {
        /**
         * Executes when the component is created.
         * Fetches the chat room details, messages, and sets up Echo event listeners.
         */
        this.roomIdLink = window.location.href.split("/").pop();
        this.getRoom();
        this.getMessages();

        const likesChannel = Echo.channel("like." + this.roomIdLink);
        likesChannel.listen(".like.new", (e) => {
            this.getMessages();
        });

        const chatChannel = Echo.channel("chat." + this.roomIdLink);
        chatChannel.listen(".message.new", (e) => {
            this.getMessages();
        });

        const liveChannel = Echo.channel("live.status");
        liveChannel.listen(".live.status.new", (e) => {
            this.getRoom();
        });
    },
};
</script>
