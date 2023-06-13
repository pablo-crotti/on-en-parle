<script>
export default {
    data: function () {
        return {
            likes: 0,
        };
    },
    props: ["room"],
    methods: {
        handelTextLenght(text) {
            if (window.innerWidth < 768) {
                return text.length > 70 ? text.substring(0, 70) + "..." : text;
            } else {
                return text.length > 300
                    ? text.substring(0, 300) + "..."
                    : text;
            }
        },
        getLikes() {
            axios
                .get("/chat/room/" + this.room.id + "/likes")
                .then((response) => {
                    this.likes = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    created() {
        this.getLikes();

        const likesChannelTransmission = Echo.channel("like.rooms");
        likesChannelTransmission.listen(".like.rooms.new", (e) => {
            this.getLikes();
        });
    },
};
</script>
<template>
    <div class="img-container">
        <img :src="room.image" alt="" />
        <div class="likes">
            <span class="material-symbols-outlined">favorite</span>
            <p>{{ likes }}</p>
        </div>
    </div>
    <div class="infos">
        <div class="det-container">
            <p>{{ new Date(room.broadcast_date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' }) }}</p>


            <div v-if="room.on_air" class="live">
                <span class="material-symbols-outlined"> album </span>
            </div>
            <div v-else-if="new Date(room.broadcast_date) > new Date()" class="upcoming">
                <p>Prochainement</p>
            </div>
        </div>

        <h1>{{ room.title }}</h1>
        <p>
            {{ handelTextLenght(room.description) }}
        </p>
    </div>
</template>
