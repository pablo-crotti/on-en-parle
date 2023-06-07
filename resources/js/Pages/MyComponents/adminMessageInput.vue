<template>
    <input type="text" v-model="message" @keyup.enter="sendMessage()" />
    <div class="button-container">
        <button @click="sendMessage()">
            <span class="material-symbols-outlined">send</span>
        </button>
    </div>
</template>

<script>
export default {
    props: ["room", "user"],
    data: function () {
        return {
            message: "",
        };
    },
    methods: {
        sendMessage() {
            if (this.message.trim() === "") {
                return;
            }
            axios
                .post("/chat/room/" + this.room + "/admin-message", {
                    message: this.message,
                    userId: this.user,
                })
                .then((response) => {
                    if (response.status == 201) {
                        this.message = "";
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
