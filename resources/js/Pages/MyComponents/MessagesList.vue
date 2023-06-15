<template>
    <div
        draggable="true"
        @dragstart="$emit('dragstart', $event, message.id)"
        class="message-wrapper"
    >
        <div
            v-if="isAudio"
            class="message-header"
            :style="{ backgroundColor: this.headerColor }"
        >
            <span class="symbol-header material-symbols-outlined">mic</span>
        </div>

        <div
            v-else
            @onload="couleur(headercolors[1])"
            class="message-header"
            :style="{ backgroundColor: this.headerColor }"
        >
            <span class="symbol-header material-symbols-outlined">textsms</span>
        </div>

        <p v-if="isAudio">
            <span class="audio-duration"> Durée du player</span>

            {{ audiofiles[0].audio_files[0] }}
        </p>
        <p v-else>{{ message.content }}</p>
        <div class="buttons">
            <button @click="$emit('modify')">Modifier</button>
            <button @click="$emit('delete')">Supprimer</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: Object,
            default: () => ({}),
        },
        audiofiles: {
            type: Array,
            default: () => [],
        },
    },

    data() {
        return {
            headerColor: "",
            headercolors: [["audio", "message"]],
        };
    },

    methods: {
        filterMessages() {
            this.filteredMessages = this.messages.filter((message) => {
                return !this.audiofiles.some(
                    (audiofile) => audiofile.id === message.id
                );
            });
        },

        audioLoaded(event) {
            const audio = event.target;
            const duration = Math.floor(audio.duration);
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            this.audioDuration = `${minutes
                .toString()
                .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
        },

        verif() {
            console.log("verif");
        },

        couleur(type) {
            if (type === "message") {
                this.headerColor = "#8239DF";
            } else if (type === "audio") {
                this.headerColor = "#FBEF85";
            }
        },
    },

    computed: {
        isAudio() {
            return this.audiofiles.some(
                (audio) => audio.id === this.message.id
            );
        },
        audioFile() {
            const audio = this.audiofiles.find(
                (audio) => audio.id === this.message.id
            );
            return audio ? audio.audio_files[0] : null;
        },
    },
    created() {},
};
</script>
