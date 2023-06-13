<template>
  <div class="containercreer" @click="closeForm($event)">
    
        <div class="add-product" :class="{'open': formOpen}">
        <div class="button-copy" v-show="!formOpen" @click="formOpen = true">
          <span class="button-text">Créer</span>
        </div>
        <form @submit.prevent="submit">
        
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="admin-messages-title-container" style="height: 50px; justify-content: end; align-items: center;">
            <span class="symbol-header material-symbols-outlined" style="color:aliceblue;" @click="cancel">close</span>

                    </div>
        <div class="formcontent">
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
            <div class="cancel" @click="cancel">Annuler</div>
            <button type="submit" class="submit-button" >Enregistrer</button>
          </div>
        </div>
        </form>
      </div>
  </div>
  </template>
  
  
  
  <script>
  import axios from 'axios';
  export default {
    props: {
     room:{
          type: Number,
          required: true
      },
     
    },
    data() {
      return {
        formOpen: false,
        formData: {
          caller: '',
          message: '',
          status: 2,
          chat_room_id:this.room,
  
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

.admin-messages-title-container{
  display: flex;
  background-color: #B2171B;
  padding: 15px;
  margin-bottom: 10px;
}
  .formcontent{
    padding: 5px 20px 20px 20px;
    height: 100%;
    overflow: hidden;

  }
  .add-product {
    display: flex;
    flex-direction: column;
    align-items: left;
    transition: all 0.3s ease;
    background-color: #B2171B;
    border-radius: 72px;
    box-shadow: 0 4px 16px 0 rgba(0, 0, 0, .07);
    cursor: pointer;
    height: 40px;
    width: 114px;
  }
  
  .add-product.open {
    background-color: #FAFAFA;
    border-radius: 5px;
    width: 420px;
    max-height: 460px;
    height: auto;
    cursor: default;
    border-radius: 20px;
margin-top: 50px;
  }
  
  .add-product form {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    opacity: 0;
    height: 0;
    transition: opacity 0.1s ease;
  }
  
  .add-product.open form {
    opacity: 1;
    transition-delay: 0.3s;
    height: auto;

  
  }
  

  

  .form--field{
    margin-bottom: 5px;
    max-height: 140px;
  }
  .button-text {
    color: #ffffff;
    line-height: 100px;
  }
  
  .cancel {
    background-color: #ffffff;
    height: 50px;
    width: 90px ;
    border-radius: 20px;
    border: 1px solid #000000;
    color: #000000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85em;
    font-weight: bold;  }
  
  .cancel span:hover {
    text-decoration: underline;
  }
  .containercreer{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 30px;
    width: auto ;
    background-color: rgba(70, 69, 69, 0.5);
    z-index: 1;
  }
  .submit-button {
    background-color: #5d5a5a;
    height: 50px;
    width: 90px ;
    border-radius: 20px;
    line-height: 30px;
    text-align: center;
    color: #fff;
  
    font-size: 0.85em;
    border: none;
    font-weight: bold;
  }
  
  .submit-button:hover {
    darken: 70%;
    cursor: pointer;
  }
  
  .form--actions {
    display: flex;
    justify-content: space-between;
    align-content: center;
    align-items: center;
    width: 80%;
    margin-bottom: 10px;
    height: auto;
    max-height: 60px;
  }
  
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
    height: auto;
    min-height:100px;
    resize: none;
    margin-bottom: 20px;
    overflow-y: scroll;
  }
  
  .form--title {
    text-align: center;
    font-size: 20px;
    margin-bottom: 10px;
    font-weight: bolder;
  }

  .form--label {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
  }
  
  </style>
  