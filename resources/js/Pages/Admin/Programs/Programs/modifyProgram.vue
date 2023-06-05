<template>
    <div class="container">
        <form @submit.prevent="submitForm" id="formulaire-emission">
            <div id="conteneurTitre"> <h2 class="form--title">Modifier une émission</h2> </div>

            <div id="conteneurformulaire">
                <div class="form--field">
                    <label class="form--label">Titre</label>
                    <input type="text" class="form--element" name="title" v-model.trim="program.title" placeholder="Ex : Raconter des histoires aux enfants: amusant et… utile!">
                </div>

                <div class="form--field">
                    <label class="form--label">Date</label>
                    <input type="date" class="form--element" v-model.trim="program.date">
                </div>

                <div class="form--field">
                    <label class="form--label">Description</label>
                    <textarea class="form--element textarea" v-model.trim="program.description" placeholder="Description ..."></textarea>
                </div>

                <div class="form--field">
                    <label class="form--label">Banière</label>
                    <input type="text" class="form--element" v-model.trim="program.image" placeholder="Entrez l'URL de la bannière">
                </div>

                <div class="form--field">
                    <label class="form--label">Fichier audio</label>
                    <input type="text" class="form--element" v-model.trim="program.audio_file" placeholder="Entrez l'URL du fichier audio">
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
import axios from 'axios';
import moment from 'moment';
export default {
    name: 'ModifyChatRoomForm', // Changement du nom du composant
    data: function () {
        return {
            program: {
                title: '',
                date: '',
                description: '',
                image: '',
                audio_file: ''
            }


        }
    },

    methods: {
        cancel() {
            this.program = {
                title: '',
                date: '',
                description: '',
                banner: '',
                audio: '',
            };
        },
        fetchEmission(roomId) {
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
    },
created() {
    this.fetchEmission(2) // La valeur devra changer en fonction de l'émission sur laquel se trouve le bouton
}
}
</script>


<style scoped>
.container {
    display: flex;
    justify-content: center;
    height: 95%;
    width: 60vw;
    align-items: center;
    background-color: rgb(239, 239, 239);
    border-radius: 20px;
}


#formulaire-emission{
    width: 100%;
}
.add-emission {
    display: flex;
    flex-direction: column;
    align-items: left;
    justify-content: left;
    transition: all 0.3s ease;
    background-color: #B2171B;
    border-radius: 72px;
    box-shadow: 0 4px 16px 0 rgba(0, 0, 0, .07);
    cursor: pointer;
    height: 80px;
    width: 144px;
}

.add-emission.open {
    background-color: #FAFAFA;
    padding: 18px 32px;
    border-radius: 5px;
    width: 820px;
    height: 830px;
    cursor: default;
}

.add-emission form {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    opacity: 0;
    height: 0;
    overflow: hidden;
    transition: opacity 0.1s ease;
}

.add-emission.open form {
    opacity: 1;
    transition-delay: 0.3s;
    height: auto;
}

.button-copy {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
}

.button-text {
    color: #ffffff;
    line-height: 100px;
}

.cancel {
    font-size: 20px;
    cursor: pointer;
    margin-top: 20px;
}

.cancel span:hover {
    text-decoration: underline;
}

.submit-button {
    background-color: #B2171B;
    height: 40px;
    width: 130px;
    border-radius: 20px;
    line-height: 30px;
    text-align: center;
    color: #fff;
    border: none;
    font-weight: 800;
    font-size: 17px;
    margin-top: 20px;
}

.submit-button:hover {
    background-color: darken(#B2171B, 70%);
    cursor: pointer;
}

.form--actions {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin-top: 40px;
}

.form--field {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin-bottom: 24px;
}
.form--actions {
    width: 100%;
    margin-top: 20px;
    margin-bottom: 0px;
}

.form--element {
    background-color: #fff;
    border: 1px solid #FAFAFA;
    border-radius: 3px;
    font-size: 14px;
    line-height: 28px;
    padding: 0 4px;
    color: #3D3D3D;
    width: 100%;
    margin: 4px 0;
    box-sizing: border-box;
    font-family: 'Open Sans', sans-serif;
}

.form--element:focus {
    border: 1px solid #B2171B;
    border-radius: 2px;
}

.form--element:not(.textarea) {
    height: 30px;
}

.form--element.textarea {
    height: 100px;
    resize: none;
}

.form--title {
    text-align: center;
    font-size: 30px;
    margin-bottom: 10px;
    color: #121212;

}

.form--label {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
    text-align: left;
}


#conteneurformulaire {
    color: #121212;
    text-align: center;
    font-size: 30px;
    margin-bottom: 20px;
    padding: 20px;
}

</style>

