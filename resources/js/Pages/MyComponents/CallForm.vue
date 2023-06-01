<template>
<div class="container" @click="closeForm($event)">
      <div class="add-product" :class="{'open': formOpen}">
      <div class="button-copy" v-show="!formOpen" @click="formOpen = true">
        <span class="button-text">Créer</span>
      </div>
      <form @submit.prevent="submit">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h2 class="form--title">Créer une question</h2>
        <div class="form--field">
          <label class="form--label">Pseudo</label>
          <input type="text" class="form--element" name="caller" v-model.trim="formData.caller" placeholder="Ex : Jean">
        </div>
        <div class="form--field">
          <label class="form--label">Question *</label>
          <textarea class="form--element textarea" name="message" v-model.trim="formData.message" placeholder="Question à poser ..." required></textarea>
        </div>
        <div class="form--actions">
          <div class="cancel"><span @click="cancel">Annuler</span></div>
          <button type="submit" class="submit-button" >Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</template>



<script>
import axios from 'axios';
export default {
  data() {
    return {
      formOpen: false,
      formData: {
        caller: '',
        message: '',
        status:2,
        chat_room_id: 1,

      }
    }
  },
  methods: {
    cancel() {
        this.formOpen = false;
        this.formData.caller = '';
        this.formData.message = '';
      
    },
    closeForm(event) {
    if (event.target.classList.contains('container')) {
      this.formOpen = false;
    }
  },
    submit() {

        axios.post('/phone-calls', this.formData)
            .then(response => {

                if (response.status === 200) {
                    this.cancel();
                    this.$inertia.reload();
                    console.log("carré");

                }
            })
            .catch(error => {
                console.log(error);
            });
    }
} 
}
</script>

<style scoped>
.container {
  display: flex;
  align-items: left;
  justify-content: center;
  height: 100%;
  width: 100%;
}

.add-product {
  display: flex;
  flex-direction: column;
  align-items: left;
  justify-content: left;
  transition: all 0.3s ease;
  background-color: #B2171B;
  border-radius: 72px;
  box-shadow: 0 4px 16px 0 rgba(0, 0, 0, .07);
  cursor: pointer;
  height: 60px;
  width: 144px;
}

.add-product.open {
  background-color: #FAFAFA;
  padding: 18px 32px;
  border-radius: 5px;
  width: 420px;
  height: 430px;
  cursor: default;
}

.add-product form {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 100%;
  opacity: 0;
  height: 0;
  overflow: hidden;
  transition: opacity 0.1s ease;
}

.add-product.open form {
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
  font-size: 13px;
  cursor: pointer;
}

.cancel span:hover {
  text-decoration: underline;
}

.submit-button {
  background-color: #B2171B;
  height: 30px;
  width: 90px ;
  border-radius: 20px;
  line-height: 30px;
  text-align: center;
  color: #fff;

  font-size: 0.75em;
  border: none;
  font-weight: 700;
}

.submit-button:hover {
  darken: 70%;
  cursor: pointer;
}

.form--actions {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-top: 1em;
}

.form--field,
.form--actions {
  width: 100%;
}

.form--element {
  background-color: #fff;
  border: 1px solid #ECECEC;
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
  height: 200px;
  resize: none;
}

.form--title {
  text-align: center;
  font-size: 20px;
  margin-bottom: 10px;
}

</style>
