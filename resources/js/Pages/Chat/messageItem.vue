<template>
    <div class="left">
        <h2 v-if="message.text[0]" class="text-message">Question</h2>
        <h2 v-else class="transcription-message">Transcription</h2>
        <p>{{ message.content }}</p>
        <audio controls v-if="audioFile">
            <source :src="audioFile" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
        <!-- <audio controls >
            <source :src="audioFile" type="audio/mp3">
            Your browser does not support the audio element.
        </audio> -->
    </div>
    <div class="right">
        <p>{{ formatRelativeTime(message.created_at) }}</p>
        <button
            :id="'like-' + message.id"
            @click="like()"
            :class="{ liked: isLiked() }"
        >
            <span class="material-symbols-outlined">favorite</span>
        </button>
        <p>{{ message.nb_likes }}</p>
        <p></p>
    </div>
</template>

<script>
import axios from "axios";
import { formatRelativeTime } from "../../utils/timeFormat.js";
import { setItem, getItem, unsetItem } from "../../utils/local.js";

export default {
    props: ["message"],
    data: function () {
        return {
            localStorage: "1234",
            audioFile: ""
        };
    },
    methods: {
        setItem,
        getItem,
        unsetItem,
        formatRelativeTime,
        like() {
            if (this.isLiked()) {
                axios
                    .post("/chat/unlike/" + this.message.id, {})
                    .then((response) => {
                        if (response.status == 200) {
                            unsetItem(this.message.id);
                            document
                                .getElementById("like-" + this.message.id)
                                .classList.remove("liked");
                        }
                    });
                return;
            } else {
                axios
                    .post("/chat/like/" + this.message.id, {})
                    .then((response) => {
                        if (response.status == 200) {
                            setItem(this.message.id);
                            document
                                .getElementById("like-" + this.message.id)
                                .classList.add("liked");
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        isLiked() {
            if (getItem(this.message.id)) {
                return true;
            }
        },
        getAudioPath() {
            axios.get("audio/name/" + this.message.id).then((response) => {
                if (response.status == 200) {
                    // console.log(response.data)
                    this.audioFile = `${window.location.origin}/storage/rec/${response.data}`;
                }
            });
        },
    },
    created() {

        // console.log(this.message);
        this.getAudioPath();
    },
};
</script>
