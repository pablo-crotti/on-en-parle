<template>
    <div class="program-item" @click="setCurrentProgram(program.id)">
        <div class="program-header">
            <img class="program-cover" :src="program.image" />
            <div class="program-date-text">
                {{
                    new Date(program.broadcast_date).toLocaleDateString(
                        "fr-FR",
                        { weekday: "long" }
                    )
                }}
                <br />
                {{
                    new Date(program.broadcast_date).toLocaleDateString(
                        "fr-FR",
                        { day: "2-digit", month: "long", year: "numeric" }
                    )
                }}
            </div>
        </div>
        <div class="program-info">
            <span class="program-title">{{ program.title }}</span>
            <div class="program-interactions">
                <span class="material-symbols-outlined"> forum </span>
                <span>{{ room.messages_count }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        program: {
            type: Object,
        },
        currentURL: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            room: null,
        };
    },
    methods: {
        /**
         * Sets the current program by updating the URL with the new program ID.
         * @param {number} newId - The ID of the new program.
         */
        setCurrentProgram(newId) {
            const regex = /\d+$/;
            const newURL = this.currentURL.replace(regex, newId);
            window.location.replace(newURL);
        },
    },
    created() {
        axios.get("/chat/rooms-list").then((response) => {
            this.room = response.data.find(
                (item) => item.id === this.program.id
            );
        });
    },
};
</script>
