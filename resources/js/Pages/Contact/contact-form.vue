<script>

    export default {
        data () {
            return {
                formData: {
                    nom: '',
                    email: '',
                    avis: '',
                    captcha: ''
                }
            }
        },
        methods: {
            submitForm() {
                console.log(this.formData);
      // Envoyer les données du formulaire au backend (Laravel) pour traitement
      axios.post('/contact/send', this.formData)
        .then(response => {
          // Traitement réussi, afficher un message de succès ou effectuer une redirection
          console.log(response.data);
        })
        .catch(error => {
          // Traitement en erreur, afficher un message d'erreur ou effectuer une action appropriée
          console.error(error);
        });
    }
        }
    }
</script>

<template>
    <div class="contactform-container">
        <h1 class="form-title">Contactez nous</h1>
        <p class="form-desc">Nous attendons vos retours avec impatience ! Votre avis compte. Contactez-nous pour partager vos commentaires, suggestions ou questions. Nous sommes là pour vous écouter et améliorer votre expérience d'écoute. Merci de votre fidélité !</p>
        <form id="form-contact" method="post" action="#" @submit.prevent="submitForm">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" v-model="formData.nom">

            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" placeholder="Votre email" v-model="formData.email">

            <label for="avis">Votre avis *</label>
            <textarea id="avis" name="avis" placeholder="Votre avis" style="height:200px" v-model="formData.avis"></textarea>

            <label for="captcha">Captcha *</label>
            <input type="text" id="captcha" name="captcha" placeholder="Captcha" v-model="formData.captcha">

            <button class="submit-btn" type="submit">Envoyer</button>
        </form>
    </div>
</template>

