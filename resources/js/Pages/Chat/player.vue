<template>
    <button
        class="player-displayer"
        @click="playerDisplayer()"
        data-display-player="false"
    >
        <div class="bar" id="bar_1"></div>
        <div class="bar" id="bar_2"></div>
        <div class="bar" id="bar_3"></div>
    </button>
    <div class="player-container" data-player-container="false">
        <button class="close-player-button" @click="playerDisplayer()">
            <span class="material-symbols-outlined"> cancel </span>
        </button>
        <img :src="room.image" />
        <input type="range" class="player-progress-bar" />
        <div class="controlls">
            <button class="back">
                <span class="material-symbols-outlined"> replay_10 </span>
            </button>
            <button>
                <span class="material-symbols-outlined play-icon-second">
                    play_circle
                </span>
            </button>
            <button class="forward">
                <span class="material-symbols-outlined"> forward_10 </span>
            </button>
        </div>
    </div>
</template>
<script>
export default {
    props: ["room", "status"],
    methods: {
        /**
         * Toggles the visibility of the player container and player displayer elements.
         */
        playerDisplayer() {
            const playerContainer = document.querySelector(".player-container");
            const playerDisplayer = document.querySelector(".player-displayer");
            if (playerContainer.dataset.playerContainer === "false") {
                playerContainer.dataset.playerContainer = "true";
                playerDisplayer.dataset.displayPlayer = "false";
            } else {
                playerContainer.dataset.playerContainer = "false";
                playerDisplayer.dataset.displayPlayer = "true";
            }
        },

        /**
         * Displays or hides the player button based on the status.
         */
        displayPlayerButton() {
            if (this.status) {
                document.querySelector(
                    ".player-displayer"
                ).dataset.displayPlayer = "true";
            } else {
                document.querySelector(
                    ".player-displayer"
                ).dataset.displayPlayer = "false";
            }
        },
    },
    watch: {
        /**
         * Watches the 'status' property for changes and updates the player button display accordingly.
         */
        status() {
            this.displayPlayerButton();
        },
    },
};
</script>
