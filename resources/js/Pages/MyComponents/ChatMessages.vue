<template>
    <div
        draggable="true"
        @dragstart="$emit('dragstart', $event, message.id)"
        class="message-wrapper"
    >
        <div
            v-if="isCall"
            class="message-header"
            :style="{ backgroundColor: '#FC9E5A' }"
        >
            <span class="symbol-header material-symbols-outlined">call</span>
            <span
                class="message-caller"
                style="font-weight: bold; color: white"
                >{{ this.message.call[0].caller }}</span
            >
        </div>

        <div
            v-else-if="isAudio"
            class="message-header"
            :style="{ backgroundColor: '#FBEF85' }"
        >
            <span class="symbol-header material-symbols-outlined">mic</span>
            <span v-if="audioDuration" class="audio-duration"
                >{{ audioDuration }} s.</span
            >
        </div>

        <div
            v-else-if="isText"
            class="message-header"
            :style="{ backgroundColor: '#8239DF' }"
        >
            <span class="symbol-header material-symbols-outlined">textsms</span>
        </div>

        <div class="message-body">
            <div v-if="isAudio && !isCall" class="message-content">
                <p>
                    <span v-if="!editing" class="message-text">
                        {{ message.content }}
                    </span>
                    <textarea
                        v-else
                        type="text"
                        v-model="message.content"
                        class="modiftextarea"
                        @keyup.enter="saveChanges"
                    >
                    </textarea>
                </p>
                <audio
                    v-if="chiffre == 0"
                    controls
                    @canplaythrough="audioLoaded($event, message)"
                >
                    <source
                        :src="getAudioPath(message.audio[0].audio_file)"
                        type="audio/mpeg"
                    />
                </audio>
            </div>

            <div v-else class="message-content">
                <p v-if="!editing" class="message-text">
                    {{ message.content }}
                </p>
                <textarea
                    v-else
                    type="text"
                    v-model="message.content"
                    class="modiftextarea"
                    @keyup.enter="saveChanges"
                ></textarea>
                <div
                    style="display: flex; flex-direction: row; margin-top: 5px"
                >
                    <span
                        v-if="isText"
                        class="material-symbols-outlined"
                        style="
                            color: brown;
                            display: flex;
                            justify-content: center;
                        "
                        title="Nombre de like"
                        >Favorite</span
                    >
                    <p
                        v-if="isText"
                        style="
                            font-size: 13px;
                            display: flex;
                            align-items: center;
                        "
                    >
                        {{ message.nb_likes }}
                    </p>
                </div>
            </div>

            <div
                v-if="message.status !== 10 && message.status !== 5"
                class="message-actions"
            >
                <div style="display: flex; flex-direction: column">
                    <span
                        v-if="editing == false"
                        class="material-symbols-outlined"
                        style="margin-right: 3px"
                        @click="editing = !editing"
                        title="Modifier"
                        >edit</span
                    >
                    <span
                        v-else
                        class="material-symbols-outlined"
                        @click="saveChanges"
                        title="Sauvegarder"
                        >save</span
                    >

                    <span
                        class="material-symbols-outlined"
                        @click="$emit('delete', message)"
                        title="Archiver"
                        >archive</span
                    >
                </div>
            </div>

            <div v-else-if="message.status === 10">
                <span
                    class="material-symbols-outlined"
                    @click="$emit('archive', message)"
                    title="Désarchiver à inbox"
                    >unarchive</span
                >
                <button
                    v-if="isInAnimator"
                    class="material-symbols-outlined"
                    @click="$emit('cacher', message)"
                    title="Cacher le message"
                >
                    hide
                </button>
            </div>
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

        calls: {
            type: Array,
            default: () => [],
        },
        isInAnimator: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            headerColor: "",
            headercolors: [["audio", "message", "call"]],
            editing: false,
            audioDuration: "",
            chiffre: 0,
        };
    },

    methods: {
        saveChanges() {
            this.$emit("modify", this.message);
            this.editing = false;
        },
        filterMessages() {
            this.filteredMessages = this.messages.filter((message) => {
                return !this.audiofiles.some(
                    (audiofile) => audiofile.id === message.id
                );
            });
        },

        audioLoaded(event, message) {
            const audio = event.target;
            if (audio) {
                this.audioDuration = Math.floor(audio.duration);
            }
        },

        couleur(type) {
            if (type === "message") {
                this.headerColor = "#8239DF";
            } else if (type === "audio") {
                this.headerColor = "#FBEF85";
            } else if (type === "call") {
                headerColor = "#FC9E5A";
                headerSymbol = "call";
            }
        },
        getAudioPath(audioFile) {
            return `${window.location.origin}/audio/${audioFile}`;
        },
    },

    computed: {
        isAudio() {
            let grr;

            return this.message.audio && this.message.audio.length > 0;
        },
        isCall() {
            return this.message.call && this.message.call.length > 0;
        },
        isText() {
            return (
                (!this.message.audio || this.message.audio.length === 0) &&
                (!this.message.call || this.message.call.length === 0)
            );
        },
        audioFile() {
            const audio = this.audiofiles.find(
                (audio) => audio.id === this.message.id
            );
            return audio ? audio.audio_files[0] : null;
        },
    },
    created() {
        this.couleur(this.headercolors[1]);
    },
};
</script>
