<template>
    <div class="form-container">
        <form @submit.prevent="submitForm" class="formulaire-emission">
            <div class="conteneurTitre"> <h2 class="form-title">Modifier une émission</h2> </div>

            <div class="conteneurformulaire">
                <div class="form-field">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-element" name="title" v-model.trim="program.title" placeholder="Ex : Raconter des histoires aux enfants: amusant et… utile!">
                    <div class="error-message">{{ formErrors.title }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-element" v-model.trim="program.date">
                    <div class="error-message">{{ formErrors.date }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Description</label>
                    <textarea class="form-element textarea" v-model.trim="program.description" placeholder="Description ..."></textarea>
                    <div class="error-message">{{ formErrors.description }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Banière</label>
                    <input type="text" class="form-element" v-model.trim="program.image" placeholder="Entrez l'URL de la bannière">
                    <div class="error-message">{{ formErrors.banner }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Fichier audio</label>
                    <input type="text" class="form-element" v-model.trim="program.audio_file" placeholder="Entrez l'URL du fichier audio">
                </div>

                <div class="form-actions">
                    <div class="cancel"><span @click="cancel">Annuler</span></div>
                    <button type="submit" class="submit-button">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
    name: 'ModifyChatRoomForm',
    data: function () {
        return {
            program: {
                title: '',
                date: '',
                description: '',
                image: '',
                audio_file: '',
            },
            roomIdForEdit: window.location.href.split('/').pop(),
            formErrors: {}
        }
    },
    methods: {
        cancel() {
            window.history.back();
        },
        fetchEmission(roomId) { //roomId
            axios.get('/chat/room/'+ roomId)
                .then(response => {
                    this.program = response.data;
                    this.program.date = moment(this.program.date).format('YYYY-MM-DD');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        submitForm() {
            this.formErrors = {};

            if (!this.program.title) {
                this.formErrors.title = 'Le titre est obligatoire';
            };
            if (!this.program.date) {
                this.formErrors.date = 'La date est obligatoire';
            };
            if (!this.program.description) {
                this.formErrors.description = 'La description est obligatoire';
            };
            if (!this.program.image) {
                this.formErrors.banner = 'La bannière est obligatoire';
            };

            if (Object.keys(this.formErrors).length > 0) {
                return;
            } else {
                axios.post('/chat/room/edit/' + this.program.id, {
                    title: this.program.title,
                    date: this.program.date,
                    description: this.program.description,
                    banner: this.program.image,
                    audio: this.program.audio_file
                })
                    .then(response => {
                        if (response.status == 200) {
                            this.program = {
                                title: '',
                                date: '',
                                description: '',
                                image: '',
                                audio_file: ''
                            };
                            this.$emit('messagesent');
                            console.log("c'est modifié chef!")
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    },
created() {
    this.fetchEmission(this.roomIdForEdit) // La valeur devra changer en fonction de l'émission sur laquelle se trouve le bouton
}
}
</script>
