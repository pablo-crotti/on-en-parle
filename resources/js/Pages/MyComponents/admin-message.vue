<template>
    <div
        class="message-wrapper"
        :id="`message-${message.id}`"
        draggable="true"
        @dragstart="$emit('dragstart', $event, message.id)"
    >
        <div class="message-header" :style="{ backgroundColor: headerColor }">
            <div>
                <span class="symbol-header material-symbols-outlined">{{
                    headerSymbol
                }}</span>
                <span v-if="message.audio.length > 0" class="audio-duration">{{
                    this.audioDuration
                }}</span>
                <span v-if="message.call.length > 0" class="message-caller">{{
                    message.call[0].caller
                }}</span>
            </div>

            <div
                v-show="isArchived(message.status)"
                class="aired-status"
                :style="{ backgroundColor: airedStatusColor }"
            >
                {{ statusName }}
            </div>
        </div>

        <div class="message-body">
            <span class="message-date">{{ getFormattedDate(message) }}</span>

            <div v-if="message.audio.length > 0 && message.audio[0].audio_file">
                <audio controls @loadedmetadata="audioLoaded">
                    <source
                        :src="getAudioURL(message.audio[0].audio_file)"
                        type="audio/webm"
                    />
                    Your browser does not support the audio element.
                </audio>
            </div>
            <div class="message-content">
                <p v-if="!editing" class="message-text">
                    {{ message.content }}
                </p>

                <textarea
                    v-else
                    type="text"
                    v-model="message.content"
                    class="modiftextarea"
                    @keyup.enter="saveChanges"
                >
                </textarea>

                <div
                    v-if="message.status !== 10 && message.status !== 5"
                    class="message-actions"
                >
                    <span
                        class="material-symbols-outlined"
                        @click="editing = !editing"
                        >edit {{ message.status }}</span
                    >
                    <span
                        class="material-symbols-outlined"
                        @click="$emit('delete', message)"
                        >delete</span
                    >
                </div>

                <div v-else-if="message.status === 10">
                    <span
                        class="material-symbols-outlined archiveIcon"
                        @click="$emit('archive', message)"
                        >unarchive</span
                    >
                </div>
            </div>

            <div v-if="message.nb_likes > 0" class="message-likes">
                <span class="nb-likes">{{ message.nb_likes }}</span>
                <span class="message-like-symbol material-symbols-outlined"
                    >favorite</span
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["message"],
    data() {
        return {
            audioDuration: "00:00",
            editing: false,
        };
    },
    methods: {
        /**
         * Returns a formatted date string from the message's updated_at property.
         * @param {Object} message - The message object.
         * @returns {string} - The formatted date string.
         */
        getFormattedDate(message) {
            const dateString = message.updated_at;
            const date = new Date(dateString);

            const day = String(date.getDate()).padStart(2, "0");
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const year = date.getFullYear();

            const formattedDate = `${day}/${month}/${year}`;

            return formattedDate;
        },
        /**
         * Returns the status name based on the provided status value.
         * @param {number} status - The status value.
         * @returns {string} - The status name.
         */
        getStatusName(status) {
            let airedStatusColor;
            switch (status) {
                case 5:
                    airedStatusColor = "#999999";
                    return "Diffusé";
                case 10:
                    airedStatusColor = "#B2171B";
                    return "Non diffusé";
                default:
                    return "";
            }
        },
        /**
         * Checks if the message is archived based on the status value.
         * @param {number} status - The status value.
         * @returns {boolean} - True if the message is archived, false otherwise.
         */
        isArchived(status) {
            return status === 10 || status === 5;
        },
        /**
         * Handles the "loaded" event of the audio element to set the audio duration.
         * @param {Event} event - The "loaded" event.
         */
        audioLoaded(event) {
            const audio = event.target;
            const duration = Math.floor(audio.duration);
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            this.audioDuration = `${minutes
                .toString()
                .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
        },
        /**
         * Saves the changes made to the message.
         */
        saveChanges() {
            this.$emit("modify", this.message);
            this.editing = false;
        },
        /**
         * Returns the URL for the audio file.
         * @param {string} audioFile - The audio file name.
         * @returns {string} - The URL for the audio file.
         */
        getAudioURL(audioFile) {
            return `/audio/${audioFile}`;
        },
    },

    setup(props) {
        const message = props.message;

        const setStatus = (status) => {
            let airedStatusColor;
            let statusName;
            switch (status) {
                case 5:
                    airedStatusColor = "#999999";
                    statusName = "Diffusé";
                    break;
                case 10:
                    airedStatusColor = "#B2171B";
                    statusName = "Non diffusé";
                    break;
                default:
                    return "";
            }
            return { airedStatusColor, statusName };
        };

        let headerColor = "";
        let headerSymbol = "";
        let airedStatusColor = "";
        let statusName = "";

        if (message.text.length > 0) {
            headerColor = "#8239DF";
            headerSymbol = "textsms";
        } else if (message.audio.length > 0) {
            headerColor = "#FBEF85";
            headerSymbol = "mic";
        } else if (message.call.length > 0) {
            headerColor = "#FC9E5A";
            headerSymbol = "call";
        }

        ({ airedStatusColor, statusName } = setStatus(message.status));

        return {
            headerColor,
            headerSymbol,
            airedStatusColor,
            statusName,
        };
    },
};
</script>

<style>
.container {
    padding: 10px;
}
</style>
