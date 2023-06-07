<script>
import modifyProgram from "@/Pages/Admin/Programs/Programs/modifyProgram.vue";
export default {
    components: {
        modifyProgram
    },

    data: function () {
        return {
            likes: 0,
            roomIdForEdit: window.location.href.split('/').pop()
        }
    },
    props: ['room'],
    methods: {
        handelTextLenght(text) {
            if (window.innerWidth < 768) {
                return text.length > 70 ? text.substring(0, 70) + '...' : text
            } else {
                return text.length > 300 ? text.substring(0, 300) + '...' : text
            }
        },
        getLikes() {
            axios.get('/chat/room/' + this.room.id + '/likes')
                .then(response => {
                    this.likes = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        openEditPage() {
            const currentURL = window.location.href;
            const modifiedURL = currentURL.replace(/\/questions\/\d+/, '/admin/programs/modify/' + this.room.id);
            window.location.href = modifiedURL;

        }
    },
    created() {
        this.getLikes()

        const likesChannelTransmission = Echo.channel('like.rooms');
        likesChannelTransmission.listen('.like.rooms.new', (e) => {
            this.getLikes();
        });
    },

}
</script>
<template>
    <div className="img-container">
        <img :src="room.image" alt="">
        <div className="likes">
            <span class="material-symbols-outlined" @click="openEditPage">edit</span>
            <span className="material-symbols-outlined">favorite</span>
            <p>{{ likes }}</p>
        </div>
    </div>
    <div className="infos">
        <h1>{{ room.title }}</h1>
        <p>
            {{ handelTextLenght(room.description) }}
        </p>
    </div>
</template>
