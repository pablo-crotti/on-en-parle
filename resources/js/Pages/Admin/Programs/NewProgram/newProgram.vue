<template>
    <modalValidation :title="modalTitle" :message="modalMessage" :is-open="isModalOpen" @close="closeModal" />    <div class="form-container">
        <form @submit.prevent="submitForm" class="formulaire-emission">
            <div class="conteneurTitre"> <h2 class="form-title">Créer une émission</h2> </div>

            <div class="conteneurformulaire">
                <div class="form-field">
                    <label class="form-label">Titre*</label>
                    <input type="text" class="form-element" name="title" v-model="title" placeholder="Ex : Raconter des histoires aux enfants: amusant et… utile!">
                    <div class="error-message">{{ formErrors.title }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Date*</label>
                    <input type="date" class="form-element" v-model="date">
                    <div class="error-message">{{ formErrors.date }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Description*</label>
                    <textarea class="form-element textarea" v-model="description" placeholder="Description ..."></textarea>
                    <div class="error-message">{{ formErrors.description }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Bannière*</label>
                    <input type="text" class="form-element" v-model="banner" placeholder="Entrez l'URL de la bannière">
                    <div class="error-message">{{ formErrors.banner }}</div>
                </div>

                <div class="form-field">
                    <label class="form-label">Fichier audio</label>
                    <input type="text" class="form-element" v-model="audio" placeholder="Entrez l'URL du fichier audio">
                </div>
                <div class="error-message">*Champs obligatoires</div>
                <div class="form-actions">
                    <div class="cancel"><span @click="cancel">Annuler</span></div>
                    <button type="submit" class="submit-button">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";
import modalValidation from "@/Pages/MyComponents/modalValidation.vue";

export default {
    name: 'NewChatRoomForm',
    data: function () {
        return {
            title: '',
            date: '',
            description: '',
            banner: '',
            audio: '',
            formErrors: {},
            isModalOpen: false,
            modalTitle: 'Confirmation de la création de l\'émission',
            modalMessage: 'L\'émission à bien été créée!'
        }
    },
    components: {
        modalValidation
    },
    created() {
        axios.get('/is-live')
            .then(response => {
                
                if (response.data) {    
                    this.isModalOpen = true;
                    this.modalTitle = 'Erreur';
                    this.modalMessage = 'Vous ne pouvez pas créer une émission si une émission est en cours';
                    this.openModal();
                }
            })
            .catch(error => {
                console.error(error);
            });
    },
    methods: {
        openModal() {
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
            window.location.href = '/admin/programs/list';
        },
        cancel() {
                this.title = '';
                this.date = '';
                this.description = '';
                this.banner = '';
                this.audio = '';
            },
        submitForm() {
            this.formErrors = {};

            if(!this.title) {
                this.formErrors.title = 'Le titre est obligatoire';
            };
            if (!this.date) {
                this.formErrors.date = 'La date est obligatoire';
            };
            if(!this.description) {
                this.formErrors.description = 'La description est obligatoire';
            };
            if(!this.banner) {
                this.formErrors.banner = 'La bannière est obligatoire';
            };

            if (Object.keys(this.formErrors).length > 0) {
                return;
            } else {
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
                            this.openModal()
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    }
}
</script>
