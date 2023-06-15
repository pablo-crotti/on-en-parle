<template>
    <a :href="route('program-detail', { id: this.program.id })">
        <div class="program-wrapper">
            <div class="program-header">
                <img class="program-img" :src="program.image" />
                <div class="program-title">
                    <span>{{ status }}</span>
                    <h2>{{ program.title }}</h2>
                </div>
            </div>

            <div class="program-body">
                <p class="program-desc">
                    {{ truncateParagraph(program.description, 13) }}
                </p>

                <div class="program-bottom">
                    <div class="program-interactions">
                        <span class="material-symbols-outlined"> forum </span>

                        <span>{{ room.messages_count }}</span>
                    </div>
                    <span class="program-date" v-if="room.broadcast_date">{{
                        new Date(room.broadcast_date).toLocaleDateString(
                            "fr-FR",
                            { day: "2-digit", month: "long", year: "numeric" }
                        )
                    }}</span>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            room: {
                type: Object,
            },
            status: "",
        };
    },
    props: {
        program: {
            type: Object,
        },
    },
    methods: {
        truncateParagraph(paragraph, wordCount) {
            const words = paragraph.split(" ");

            if (words.length <= wordCount) {
                return paragraph;
            }

            const truncatedWords = words.slice(0, wordCount);

            const truncatedParagraph = truncatedWords.join(" ");
            const finalParagraph = truncatedParagraph + "...";

            return finalParagraph;
        },
    },
    created() {
        axios.get("/chat/rooms-list").then((response) => {
            this.room = response.data.find(
                (item) => item.id === this.program.id
            );
        });
        const today = new Date();
        const programDate = new Date(this.program.broadcast_date);
        if (programDate < today) {
            this.status = "Replay";
        } else {
            this.status = "A venir";
        }
    },
};
</script>
