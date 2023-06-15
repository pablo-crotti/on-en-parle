<template>
    <div>
        {{ this.programTitle }}
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            programTitle: "",
            currentURL: window.location.href,
            currentId: this.currentURL.split("/").pop(),
        };
    },
    methods: {
        getProgramTitle(programId) {
            axios
                .get("/chat/room/" + programId)
                .then((response) => {
                    this.programTitle = response.data.title;
                    return this.programTitle;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        this.getProgramTitle(this.currentId);
    },
};
</script>