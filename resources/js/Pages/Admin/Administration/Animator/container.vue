<!-- <script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout
        },
    }
</script>

<template>
    <AppLayout title="On en parle | Administration (Régie)">
        <div class="control-wrapper">

        </div>
    </AppLayout>
</template> -->



<script>
    import axios from 'axios';
    import ChatMessage from '@/Pages/MyComponents/ChatMessages.vue';
    import CallForm from '@/Pages/MyComponents/CallForm.vue';
  //  import ChatContainer from '@/Pages/MyComponents/admin-message.vue';


    export default {
        components: {
            CallForm,
             ChatMessage,
          //  ChatContainer
        },
    
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
        type: String,
        required: true
    },
    callChatroom:{
        type: Array,
        required: true
    },
   
  },

  data() {
    return {
      messages: [],
      audiofiles: this.audioChatroom,
      filteredMessages: [],
      chatroomId:null,
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
            if ([0,1,2].includes(status)) {
            // Si le statut est 0, 1 ou 2, changez-le en 5
                message.status = 5;
            } else if (status === 3) {
                // Si le statut est 3, changez-le en 10
                message.status = 10;
            }
            await axios.post(`/AdminInbox/message/${message.id}/update`, { status: message.status });
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
        affichedon(){
        },
        audioMessages() {
    return this.messages.filter(m => m.audio && m.audio.length > 0 && m.status === 5);
},
callMessages() {
    return this.messages.filter(m => m.call && m.call.length > 0 && m.status === 5);
},
textMessages() {
    return this.messages.filter(m => (!m.audio || m.audio.length === 0) && (!m.call || m.call.length === 0) && m.status === 5);
},
statusTenMessages() {
    return this.messages.filter(m => m.status === 10);
}
    },
    created() {
        this.messages = this.initialMessages;
        let convertedId = null;
        console.log(this.messages);
            if (typeof this.idroom === 'string') {
            const trimmedId = this.idroom.trim();
            if (trimmedId !== '') {
                convertedId = parseInt(trimmedId);
            }
            }

            // Utilisez la variable convertie dans votre code plutôt que this.idroom
            if (convertedId !== null) {
            this.chatroomId = convertedId;
            } else {
console.log("pas d'id recu")            }
},
  
}

</script>

<template>


  <div class="containerManagement">

        <h1>ChatRecu</h1>
        <div style="display:flex; flex-direction: row; color: azure; width:auto;align-items: center;">
         
            <button @click="sortByCreation" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Créaation</button>
          <button  @click="sortByLikes" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Like</button>

       </div>
                <div class="columns">
            <div class="column" v-for="status in [0,1,2,3]" :key="status">
                <div class="admin-messages-container-">
                    <div class="admin-messages-title-container">
                        <div class="admin-messages-title">{{ ['Message Vocal', 'Appels en attente', 'Message Text', 'Diffuser'][status] }}</div>
                    </div>
                    <div class="admin-messages-list"
                        :id="`column-${status}`"
                        @drop="drop($event, status)"
                        @dragover.prevent>

                        <div class="admin-messages-item" v-for="message in [audioMessages, callMessages, textMessages, statusTenMessages][status]">
                            <chat-message
                            :key="message.id"
                            :message="message"
                            :calls="calls"
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
            :room="chatroomId"></call-form>
        </div>

</template>




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

.containerManagement{
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
    width: 80vw;
    height: 100%;
    display: flex;
    flex-direction: row;
    padding: 20px;
     align-items: top;
    justify-content: center;
}
.column {
    width: auto;
    height: 80%;
    border: solid 1px black;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    align-content: center;
    padding: 0px;
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

    width:auto;
    height: auto;
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
.dropzone {
    width: 100%;
    height: 100%;
    border: 1px solid black;
    border-radius: 5px;
    padding: 10px;
    background-color: white;
    margin:0;
}
</style>