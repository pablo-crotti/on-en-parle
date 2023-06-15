<template>
    <modalValidation
        :title="modalTitle"
        :message="modalMessage"
        :is-open="isModalOpen"
        @close="closeModal"
    />
    <div class="contactform-container">
        <h1 class="form-title">Contactez nous</h1>
        <p class="form-desc">
            Nous attendons vos retours avec impatience ! Votre avis compte.
            Contactez-nous pour partager vos commentaires, suggestions ou
            questions. Nous sommes là pour vous écouter et améliorer votre
            expérience d'écoute. Merci de votre fidélité !
        </p>
        <form id="form-contact" method="post" @submit.prevent="submitForm">
            <label for="nom">Nom</label>
            <input
                type="text"
                id="nom"
                name="nom"
                placeholder="Votre nom"
                v-model="formData.nom"
            />

            <label for="email">Votre email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Votre email"
                v-model="formData.email"
            />

            <label for="avis"
                >Votre avis <span class="form-required">*</span></label
            >
            <textarea
                id="avis"
                name="avis"
                placeholder="Votre avis"
                style="height: 150px"
                v-model="formData.avis"
            ></textarea>
            <div class="error-message">{{ formErrors.avis }}</div>

            <label for="captcha"
                >{{ num1 }} + {{ num2 }} =
                <span class="form-required">*</span></label
            >
            <input
                type="text"
                id="captcha"
                name="captcha"
                placeholder="Captcha"
                v-model="formData.captcha"
            />
            <div class="error-message">{{ formErrors.captcha }}</div>

            <button class="submit-btn" type="submit">Envoyer</button>
        </form>
    </div>
</template>

<script>
/**
 * Component: modalValidation
 * Description: The component for displaying a validation modal.
 */
import modalValidation from "@/Pages/MyComponents/modalValidation.vue";
import axios from "axios";

export default {
    components: {
        modalValidation,
    },
    data() {
        return {
            formData: {
                nom: "",
                email: "",
                avis: "",
                captcha: "",
            },
            formErrors: {},
            num1: Math.floor(Math.random() * 10) + 1,
            num2: Math.floor(Math.random() * 10) + 1,
            isModalOpen: false,
            modalTitle: "Confirmation de l'envoi de mail",
            modalMessage: "Votre mail à bien été envoyé !",
        };
    },
    methods: {
        /**
         * Method: submitForm
         * Description: Submits the form data.
         */
        async submitForm() {
            this.formErrors = {};

            if (this.formData.avis === "") {
                this.formErrors.avis = "L'avis est requis";
            }

            if (!this.checkCaptcha()) {
                this.formErrors.captcha = "Le captcha est incorrect";
            }

            if (Object.keys(this.formErrors).length > 0) {
                return;
            } else {
                try {
                    const response = await axios.post("/contact/send", {
                        nom: this.formData.nom,
                        email: this.formData.email,
                        avis: this.formData.avis,
                        captcha: this.formData.captcha,
                    });

                    if (response.status === 200) {
                        this.openModal();
                        this.formData.nom = "";
                        this.formData.email = "";
                        this.formData.avis = "";
                        this.formData.captcha = "";
                        this.num1 = Math.floor(Math.random() * 10) + 1;
                        this.num2 = Math.floor(Math.random() * 10) + 1;
                    } else {
                        alert("Une erreur est survenue !");
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        },
        /**
         * Method: checkCaptcha
         * Description: Checks if the entered captcha is correct.
         * @returns {boolean} - Whether the captcha is correct or not.
         */
        checkCaptcha() {
            return this.formData.captcha == this.num1 + this.num2;
        },
        /**
         * Method: openModal
         * Description: Opens the validation modal.
         */
        openModal() {
            this.isModalOpen = true;
        },
        /**
         * Method: closeModal
         * Description: Closes the validation modal.
         */
        closeModal() {
            this.isModalOpen = false;
        },
    },
};
</script>
