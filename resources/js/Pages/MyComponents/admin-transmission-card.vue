<script>
import modifyProgram from "@/Pages/Admin/Programs/Programs/modifyProgram.vue";
import moment from 'moment';
import axios from "axios";
import modalConfirmation from "@/Pages/MyComponents/modalConfirmation.vue";


export default {
    components: {
        modifyProgram,
        modalConfirmation,
    },
    data: function () {
        return {
            likes: 0,
            roomIdForEdit: window.location.href.split('/').pop(),
            isModalOpen: false,
            modalTitle: 'Confirmation',
            modalMessage: 'Êtes-vous sûr de vouloir supprimer cette émission?',
            roomForComment: {
                type: Object
            }
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
        openModal() {
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
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

        },
        deleteProgram(){
            axios.post(`/chat/room/delete/`+ this.roomIdForEdit)
                .then(response => {
                    window.location.href = '/admin/programs/list';
                })

        },
        formatDate(date) {
            //moment.locale('fr'); ne marche pas donc j'ai du faire ça:
            moment.updateLocale('en', {
                months : [
                    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet",
                    "Août", "Septembre", "Octobre", "Novembre", "Décembre"
                ]
            });
            return moment(date).format('D MMMM YYYY');
        }
    },
    created() {
        this.getLikes()
        const likesChannelTransmission = Echo.channel('like.rooms');
        likesChannelTransmission.listen('.like.rooms.new', (e) => {
            this.getLikes();
        });
        axios.get(`/chat/room/${this.roomIdForEdit}/messages`).then(response => {
            this.roomForComment = response.data;
            console.log(this.roomForComment.length);
        });
    },

}
</script>
<template>
    <modalConfirmation :title="modalTitle" :message="modalMessage" :is-open="isModalOpen" @close="closeModal" @validate="deleteProgram" />
    <div className="img-container">
        <img :src="room.image" alt="">
        <div className="likes">
            <span className="material-symbols-outlined">favorite</span>
            <p>{{ likes }}</p>
            <span className="material-symbols-outlined">forum</span>
            <p>{{this.roomForComment.length}}</p>
        </div>
    </div>
    <div className="infos">
        <h1>{{ room.title }}</h1>
        <p>{{ handelTextLenght(room.description) }}</p>
        <p class="dateDiffusion">{{ this.formatDate(this.room.broadcast_date) }}</p>
    </div>
    <div class="btn-modify">
        <a :href="route('modify', {id: this.roomIdForEdit})" style="color: aliceblue;"><span @click="openEditPage">Modifier</span></a>
        <a style="color: aliceblue;"><span @click="openModal">Supprimer</span></a>
    </div>
</template>
