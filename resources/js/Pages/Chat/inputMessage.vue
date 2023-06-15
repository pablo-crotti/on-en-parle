<template>
    <modalValidation
        :title="modalTitle"
        :message="modalMessage"
        :is-open="isModalOpen"
        @close="closeModal"
    />
    <div class="input-message-container">
        <input
            id="input-message"
            data-display="true"
            type="text"
            v-model="message"
            @keyup.enter="sendMessage()"
            @keyup="handleKeyUp()"
            placeholder="Taper un message..."
        />
        <div class="audio-recorder" id="audio-recorder" data-display="false">
            <div class="recording-infos-container">
                <p>{{ recordingTime }} / 90 sec.</p>
            </div>
        </div>
        <div id="delete" data-display="false" class="input-message-button">
            <button class="" @click="deleteRecord()">
                <span class="material-symbols-outlined">delete</span>
            </button>
        </div>
        <div
            id="record-message"
            data-display="true"
            class="input-message-button"
        >
            <button class="" @click="recordMessage()">
                <span class="material-symbols-outlined">mic</span>
            </button>
        </div>
        <div
            id="send-message"
            data-display="false"
            class="input-message-button"
        >
            <button @click="sendMessage()" class="">
                <span class="material-symbols-outlined">send</span>
            </button>
        </div>
        <div id="send-record" data-display="false" class="input-message-button">
            <button @click="sendRecord()" class="">
                <span class="material-symbols-outlined">send</span>
            </button>
        </div>
        <div id="call" data-display="true" class="input-message-button">
            <button class="">
                <span class="material-symbols-outlined">call</span>
            </button>
        </div>
    </div>
</template>

<script>
/**
 * Component: modalValidation
 * Description: Represents a modal dialog for validation messages.
 */
import axios from "axios";
import modalValidation from "../MyComponents/modalValidation.vue";

export default {
    props: ["room"],
    data() {
        return {
            message: "", // The typed message
            mediaRecorder: null, // MediaRecorder instance for audio recording
            chunks: [], // Recorded audio chunks
            recordingActive: false, // Indicates if audio recording is active
            recordingTime: 0, // Current recording time in seconds
            timerInterval: null, // Interval for updating recording time
            audioDeleted: false, // Indicates if the recorded audio has been deleted
            isModalOpen: false, // Indicates if the success modal is open
            modalTitle: "Merci !", // Title of the success modal
            modalMessage:
                "Votre message nous est parvenu, s'il est accepté, il apparaitra dans le chat.", // Message of the success modal
        };
    },
    components: {
        modalValidation,
    },
    methods: {
        /**
         * Handles the keyup event when typing in the message input.
         * Updates the visibility of send and record buttons based on the message input value.
         */
        handleKeyUp() {
            if (this.message) {
                document.getElementById("send-message").dataset.display =
                    "true";
                document.getElementById("record-message").dataset.display =
                    "false";
            } else {
                document.getElementById("send-message").dataset.display =
                    "false";
                document.getElementById("record-message").dataset.display =
                    "true";
            }
        },

        /**
         * Sends the typed message to the chat room.
         * Makes a POST request to the server to send the message.
         * Emits a 'messagesent' event upon successful message submission.
         * Opens a modal dialog to indicate message submission success.
         */
        sendMessage() {
            if (this.message.trim() === "") {
                return;
            }
            axios
                .post(`/chat/room/${this.room}/message`, {
                    message: this.message,
                })
                .then((response) => {
                    if (response.status === 201) {
                        this.message = "";
                        this.$emit("messagesent");
                        this.openModal();
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        /**
         * Starts recording the audio message.
         * Gets user media permission for audio recording.
         * Initializes a media recorder and starts recording the audio stream.
         * Updates the UI to display the recording state.
         */
        recordMessage() {
            document.getElementById("call").dataset.display = "false";
            document.getElementById("send-message").dataset.display = "false";
            document.getElementById("send-record").dataset.display = "true";
            document.getElementById("input-message").dataset.display = "false";
            document.getElementById("audio-recorder").dataset.display = "true";
            document.getElementById("record-message").dataset.display = "false";
            document.getElementById("delete").dataset.display = "true";

            this.startRecording();
        },

        /**
         * Deletes the recorded audio message.
         * Updates the UI to hide the recording-related elements.
         * Stops the recording and resets the recording state.
         */
        deleteRecord() {
            document.getElementById("call").dataset.display = "true";
            document.getElementById("send-message").dataset.display = "false";
            document.getElementById("send-record").dataset.display = "false";
            document.getElementById("input-message").dataset.display = "true";
            document.getElementById("audio-recorder").dataset.display = "false";
            document.getElementById("record-message").dataset.display = "true";
            document.getElementById("delete").dataset.display = "false";

            this.audioDeleted = true;

            this.stopRecording();
            this.resetRecording();
        },

        /**
         * Sends the recorded audio message.
         * Stops the audio recording.
         * Updates the UI to hide the recording-related elements.
         */
        sendRecord() {
            this.stopRecording();

            document.getElementById("call").dataset.display = "true";
            document.getElementById("send-message").dataset.display = "false";
            document.getElementById("send-record").dataset.display = "false";
            document.getElementById("input-message").dataset.display = "true";
            document.getElementById("audio-recorder").dataset.display = "false";
            document.getElementById("record-message").dataset.display = "true";
            document.getElementById("delete").dataset.display = "false";
        },

        /**
         * Starts recording the audio stream using the MediaRecorder API.
         * Requests user media permission for audio recording.
         * Sets up event listeners for recording data and updates the recording time.
         */
        startRecording() {
            this.audioDeleted = false;
            navigator.getUserMedia =
                navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia ||
                navigator.msGetUserMedia;

            navigator.mediaDevices.getUserMedia({ audio: true }).then(
                (stream) => {
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.mediaRecorder.addEventListener(
                        "dataavailable",
                        (event) => {
                            this.chunks.push(event.data);
                            if (!this.audioDeleted) {
                                const audioBlob = new Blob(this.chunks, {
                                    type: "audio/webm",
                                });
                                const formData = new FormData();
                                formData.append("audio", audioBlob);

                                axios
                                    .post(`/store-audio/${this.room}`, formData)
                                    .then((response) => {
                                        this.openModal();
                                    })
                                    .catch((error) => {
                                        console.log(error);
                                    });
                                this.resetRecording();
                            }
                        }
                    );
                    this.mediaRecorder.start();
                    this.recordingActive = true;

                    this.timerInterval = setInterval(() => {
                        this.recordingTime++;
                        if (this.recordingTime >= 90) {
                            this.stopRecording();
                        }
                    }, 1000);
                },
                (error) => {
                    console.error(error);
                }
            );
        },

        /**
         * Stops the audio recording.
         * Clears the recording interval.
         */
        stopRecording() {
            if (this.mediaRecorder && this.recordingActive) {
                this.mediaRecorder.stop();
                this.recordingActive = false;

                clearInterval(this.timerInterval);
            }
        },

        /**
         * Resets the recording state.
         * Clears the recorded audio chunks and resets the recording time.
         */
        resetRecording() {
            this.mediaRecorder = null;
            this.chunks = [];
            this.recordingTime = 0;
        },

        /**
         * Opens the success modal dialog.
         * Sets the isModalOpen flag to true.
         */
        openModal() {
            this.isModalOpen = true;
        },

        /**
         * Closes the success modal dialog.
         * Sets the isModalOpen flag to false.
         */
        closeModal() {
            this.isModalOpen = false;
        },
    },
};
</script>

<style>
div.input-message-container input:focus,
div.input-message-container input:active,
div.input-message-container input:focus-visible,
[type="text"]:focus,
[type="email"]:focus,
[type="url"]:focus,
[type="password"]:focus,
[type="number"]:focus,
[type="date"]:focus,
[type="datetime-local"]:focus,
[type="month"]:focus,
[type="search"]:focus,
[type="tel"]:focus,
[type="time"]:focus,
[type="week"]:focus,
[multiple]:focus,
textarea:focus,
select:focus {
    outline: none !important;
    border: none !important;
}
</style>
