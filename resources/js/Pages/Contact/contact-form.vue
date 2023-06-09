<script>

    import axios from 'axios';
    import modalValidation from '@/Pages/MyComponents/modalValidation.vue';

    export default {
        data () {
            return {
                formData: {
                    nom: '',
                    email: '',
                    avis: '',
                    captcha: ''
                },
                num1: Math.floor(Math.random() * 10) + 1,
                num2: Math.floor(Math.random() * 10) + 1,
                isModalOpen: false,
                modalTitle: 'Confirmation de l\'envoi de mail',
                modalMessage: 'Votre mail à bien été envoyé !'
            }
        },
        components: {
            modalValidation
        },
        methods: {
            async submitForm() {
                if (!this.checkCaptcha()) {
                    alert('Captcha incorrect !');
                    return;
                } else {

                try {
                    const response = await axios.post('/contact/send', {
                        nom: this.formData.nom,
                        email: this.formData.email,
                        avis: this.formData.avis,
                        captcha: this.formData.captcha
                    });

                    
                    if (response.status == 200) {
                        this.openModal();
                        this.formData.nom = '';
                        this.formData.email = '';
                        this.formData.avis = '';
                        this.formData.captcha = '';
                        this.num1 = Math.floor(Math.random() * 10) + 1;
                        this.num2 = Math.floor(Math.random() * 10) + 1;
                    } else {
                        alert('Une erreur est survenue !');
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        
            },
            checkCaptcha() {
                if (this.formData.captcha == this.num1 + this.num2) {
                    return true;
                } else {
                    return false;
                }
            },
            openModal() {
                this.isModalOpen = true;
            },
            closeModal() {
            this.isModalOpen = false;
            }
        }
    }
</script>

<template>
     <modalValidation :title="modalTitle" :message="modalMessage" :is-open="isModalOpen" @close="closeModal" />
    <div class="contactform-container">
        <h1 class="form-title">Contactez nous</h1>
        <p class="form-desc">Nous attendons vos retours avec impatience ! Votre avis compte. Contactez-nous pour partager vos commentaires, suggestions ou questions. Nous sommes là pour vous écouter et améliorer votre expérience d'écoute. Merci de votre fidélité !</p>
        <form id="form-contact" method="post" @submit.prevent="submitForm">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" v-model="formData.nom">

            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" v-model="formData.email">

            <label for="avis">Votre avis <span class="form-required">*</span></label>
            <textarea id="avis" name="avis" placeholder="Votre avis" style="height:200px" v-model="formData.avis"></textarea>

            <label for="captcha">{{ num1 }} + {{ num2 }} = <span class="form-required">*</span></label>
            <input type="text" id="captcha" name="captcha" placeholder="Captcha" v-model="formData.captcha" required>

            <button class="submit-btn" type="submit">Envoyer</button>
        </form>
    </div>
</template>

