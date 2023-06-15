<template>
    <AppLayout title="On en parle | Émissions (Live)">
        <modalConfirmation
            :title="modalTitle"
            :message="modalMessage"
            :is-open="isModalOpen"
            @close="closeModal"
            @validate="goLive"
        />
        <div class="live-wrapper">
            <div class="live-content-wrapper">
                <h1 class="live-title">Émission (Live)</h1>
            </div>

            <div class="golive-wrapper">
                <div class="golive-header">
                    <span class="golive-symbol material-symbols-outlined"
                        >videocam
                    </span>
                </div>

                <div class="golive-body">
                    <div class="golive-buttons">
                        <h2 class="live-subtitle">{{ this.program.title }}</h2>
                        <span class="live-date"
                            >Emission du
                            {{
                                new Date(
                                    this.program.broadcast_date
                                ).toLocaleDateString("fr-FR", {
                                    day: "2-digit",
                                    month: "long",
                                    year: "numeric",
                                })
                            }}</span
                        >
                        <div
                            class="golive-button"
                            @click="openModal"
                            :style="{ backgroundColor: this.background }"
                        >
                            <div class="golive-rec-symbol"></div>
                            {{ this.btnCaption }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayoutAdmin
 * Description: The main layout component for the admin section.
 * 
 * Component: modalConfirmation
 * Description: A modal component for displaying confirmation messages.
 */
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import axios from "axios";
import modalConfirmation from "@/Pages/MyComponents/modalConfirmation.vue";

export default {
    components: {
        AppLayout,
        modalConfirmation,
    },
    data() {
        return {
            program: {}, // Holds the program data
            background: "", // Background color
            btnCaption: "", // Caption for the button
            isModalOpen: false, // Flag to control the modal visibility
            modalTitle: "Confirmation", // Title of the modal
            modalMessage: "Êtes-vous sûr de vouloir démarrer le Live ?", // Message displayed in the modal
        };
    },
    methods: {
        /**
         * Starts the live broadcast.
         * Makes a POST request to start the live broadcast for the program.
         * Updates the program data and checks if it is live.
         */
        goLive() {
            axios.post(`/emission/${this.program.id}/live`).then((response) => {
                this.program = response.data.chatRoom;
                this.isLive();
            });
        },

        /**
         * Checks if the program is live.
         * Sets the background color, button caption, and modal message based on the live status.
         * @returns {boolean} - True if the program is live, false otherwise.
         */
        isLive() {
            if (this.program.on_air) {
                this.background = "#838383";
                this.btnCaption = "Arrêter le live";
                this.modalMessage =
                    "Êtes-vous sûr de vouloir arrêter le Live ?";
                return true;
            } else {
                this.background = "#f1f1f1";
                this.btnCaption = "Démarrer le live";
                this.modalMessage =
                    "Êtes-vous sûr de vouloir démarrer le Live ?";
                return false;
            }
        },

        /**
         * Opens the modal.
         * Sets the isModalOpen flag to true.
         */
        openModal() {
            this.isModalOpen = true;
        },

        /**
         * Closes the modal.
         * Sets the isModalOpen flag to false.
         */
        closeModal() {
            this.isModalOpen = false;
        },
    },
    created() {
        /**
         * Retrieves the next program data.
         * Makes a GET request to get the details of the next program.
         * Updates the program data and checks if it is live.
         */
        axios.get("/prochaine-emission").then((response) => {
            this.program = response.data;
            if (this.program !== null) {
                this.isLive(this.program);
            }
        });
    },
};
</script>
