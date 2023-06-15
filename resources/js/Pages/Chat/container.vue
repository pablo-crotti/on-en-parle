<script>
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
            messages: [],
            roomIdLink: window.location.href.split("/").pop(),
            room: null,
            playerStatus: false,
        };
    },
    methods: {
        getRoom() {
            axios.get("/chat/room/" + this.roomIdLink).then((response) => {
                this.room = response.data;
            });
        },
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
    watch: {
        room() {
            this.getMessages();
        },
    },
    created() {
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
