<template>
    <div class="container">
        <form @submit.prevent="submitForm" id="formulaire-emission">
            <div id="conteneurTitre"> <h2 class="form--title">Créer une émission</h2> </div>

            <div id="conteneurformulaire">
                <div class="form--field">
                    <label class="form--label">Titre</label>
                    <input type="text" class="form--element" name="title" v-model="title" placeholder="Ex : Raconter des histoires aux enfants: amusant et… utile!">
                </div>

                <div class="form--field">
                    <label class="form--label">Date</label>
                    <input type="date" class="form--element" v-model="date">
                </div>

                <div class="form--field">
                    <label class="form--label">Description</label>
                    <textarea class="form--element textarea" v-model="description" placeholder="Description ..."></textarea>
                </div>

                <div class="form--field">
                    <label class="form--label">Bannière</label>
                    <input type="text" class="form--element" v-model="banner" placeholder="Entrez l'URL de la bannière">
                </div>

                <div class="form--field">
                    <label class="form--label">Fichier audio</label>
                    <input type="text" class="form--element" v-model="audio" placeholder="Entrez l'URL du fichier audio">
                </div>

                <div class="form--actions">
                    <div class="cancel"><span @click="cancel">Annuler</span></div>
                    <button type="submit" class="submit-button">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'NewChatRoomForm',
    data: function () {
        return {
            title: '',
            date: '',
            description: '',
            banner: '',
            audio: ''
        }
    },
    methods: {
        cancel() {
            this.formData = {
                title: '',
                date: '',
                description: '',
                banner: '',
                audio: '',
            };
        },
        submitForm() {
            console.log("test", this.title, this.date, this.description, this.banner, this.audio)

            axios.post('/chat/room/new', {
                title: this.title,
                date: this.date,
                description: this.description,
                banner: this.banner,
                audio: this.audio
            })
                .then(response => {
                    if (response.status == 201) {
                        this.title = '';
                        this.date = '';
                        this.description = '';
                        this.banner = '';
                        this.audio = '';
                        this.$emit('messagesent')
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }
}
</script>