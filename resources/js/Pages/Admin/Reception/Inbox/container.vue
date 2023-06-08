<script>
    import axios from 'axios';
    import ChatMessage from '@/Pages/MyComponents/ChatMessages.vue';
    import CallForm from '@/Pages/MyComponents/CallForm.vue';
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';


    export default {
        components: {
          CallForm,
          ChatMessage,
          AppLayout},
    
    props: {
    initialMessages:{
      type: Array,
      required: true
    },
    audioChatroom:{
        type: Array,
        required: true
    },
    idroom:{
        type: Number,
        required: true
    },
    callChatroom:{
        type: Array,
        required: true
    },
   
  },

  data() {
    return {
      messages: this.initialMessages,
      audiofiles: this.audioChatroom,
      filteredMessages: [],
      statu: ["Inbox", "Présélectionnés", "Sélectionnés", "Régie", "Prêt à diffuser"],
      calls: this.callChatroom,

    };
  },

  methods: {
    
    drag(event, messageId) {
    event.dataTransfer.setData('text', messageId);
  },

  async drop(event, status) {
    const messageId = parseInt(event.dataTransfer.getData('text'));
      const message = this.messages.find(m => m.id === messageId);

      if (message) {
        message.status = status;
        await axios.post(`/AdminInbox/message/${message.id}/update`, { status });
      }
    },
    async modifier(message){
    await axios.post(`/AdminInbox/message/${message.id}/content`, { content: message.content });
},
async deleteMessage(message) {
        try {
            const response = await axios.post(`/AdminInbox/message/${message.id}/delete`);
            // remove the message from local messages
            this.messages = this.messages.filter(m => m.id !== message.id);
        } catch (error) {
            console.error(error);
        }
    },
    filterMessages() {  // Nouvelle fonction pour filtrer les messages
                this.filteredMessages = this.messages.filter(message => {
                    return !this.audiofiles.some(audiofile => audiofile.id === message.id);
                });
            },
          sortByCreation() {
      this.messages.sort((a, b) => {
        return new Date(b.created_at) - new Date(a.created_at); // Trier par date de création en ordre décroissant
      });
      },
      sortByLikes() {
        this.messages.sort((a, b) => b.nb_likes - a.nb_likes); // Trier par nombre de likes en ordre décroissant
      },

    },
    computed: {
  
    },
    created() {
       console.log(this.messages) 
    },

}

</script>

<template>
  <AppLayout title="On en parle | Réception (Inbox)">
    <div class="containerInbox">
          <h1>ChatRecu</h1>
          <div style="display:flex; flex-direction: row; color: azure; width:auto;align-items: center;">
          
            <button @click="sortByCreation" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Créaation</button>
            <button  @click="sortByLikes" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Like</button>

          </div>
        
    <div class="columns">

        <div class="column" v-for="status in [0, 1]" :key="status">
          <div class="admin-messages-container">
            <div class="admin-messages-title-container">
              
              <div class="admin-messages-title">{{ statu[status] }}</div>
              </div>
      
            <div class="admin-messages-list "
              :id="`column-${status}`"
                      @drop="drop($event, status)"
                      @dragover.prevent
              >
                
                    <div class="admin-messages-item" v-for="message in messages.filter(m => m.status === status)">
                    
                            <chat-message
                          :key="message.id"
                          :message="message"
                          :audiofiles="audiofiles"
                          @dragstart="drag($event, message.id)"
                          @modify="modifier"
                          @delete="deleteMessage"
                        /> 
                    </div>
            
                  </div>
        
                
          
            </div>
          </div>
        </div>

    </div> 
                <div id="creernouveaumsg">
                  <call-form
                  :room="idroom"></call-form>
                </div>
  </AppLayout>
</template>

<!-- 
<style>

#creernouveaumsg{
    width: 100%;
    height: 100%;
    display: flex;
    padding: 20px;
    justify-content: center;
    align-items: center;
   }

h1 {
    font-size: 40px;
    text-align: center;
    color: white;
}

.containerInbox{
    margin-right:0;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 20px;
    justify-content: center;
    align-items: center;
    align-content: center;
    width:auto;
}
.columns {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
    padding: 20px;
    justify-content: center;
    align-items: top;
    align-content: center;
    width: 80vw;
}
.column {
    width: 100%;
    height: 80%;
    border: solid 1px black;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;
    align-content: center;
    padding: 15px;
}


.message{
    display: flex;
    flex-direction: row;
    width: 100%;
    min-height: 35px;
    margin-right: 0px;
    border:1px solid black;
    border-radius: 1px;
    width: 80%;
    font-size: 15px;
    margin-bottom: 10px;
    padding: 10px;
}
.buttons{
    display:flex;
    flex-direction: columns;
    justify-content: flex-end;
}
.input{

    width:80%;
    height: 40px;
}

.button {
    border: 1px solid black;

    border-radius: 15px;
}
.item {
    padding: 20px;
    color: white;
    width: 100%;
} 

</style> -->