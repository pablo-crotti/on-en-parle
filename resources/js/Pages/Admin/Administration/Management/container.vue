<!-- methods: {
    getMessages() {
        axios.get(`/emission/${this.no}/status/${this.id}`)
            .then(response => {
                this.messages = response.data;
                console.log(this.messages);
            })
            .catch(error => {
                console.log(error);
            });
    },
},
created() {
    this.getMessages();
} -->

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
   
  },

  data() {
    return {
      messages: [],
      audiofiles: this.audioChatroom,
      filteredMessages: [],
      statu: ["Inbox", "Présélectionnés", "Sélectionnés", "Régie", "Prêt à diffuser"],
    exemplemsg:[{
            id: 1,
            content: "Salut",
            created_at: "2023-05-31T11:13:07.000000Z",
            nb_likes: 0,
            status: 1,
            updated_at: "2023-06-05T07:28:33.000000Z"}
            ,{
                id: 2,
                content: "Salut",
                created_at: "2023-05-31T11:13:07.000000Z",
                nb_likes: 54,
                status: 0,
                updated_at: "2023-06-05T07:28:33.000000Z"}]
    };
  },

  methods: {
    getMessages() {
        axios.get(`/emission/1/status/5`)
            .then(response => {
                this.messages = response.data;
                console.log(this.messages);
            })
            .catch(error => {
                console.log(error);
            });
    },
    
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
            }


    },
    computed: {
  
    },
    created() {
    this.getMessages();
} 
}

</script>

<template>
  <div class="containerManagement">
        <h1>ChatRecu</h1>

  <div class="columns">

      <div class="column" v-for="status in [0,1,2,3,5]" :key="status">
          <div class="admin-messages-container">
              <div class="admin-messages-title-container">
                  <div class="admin-messages-title">{{ statu[status] }}</div>
              </div>
          <div class="admin-messages-list "
           :id="`column-${status}`"
                  @drop="drop($event, status)"
                  @dragover.prevent>
            
                <div class="admin-messages-item" v-for="message in messages[0].filter(m => m.status === status)">
                <!-- <p>fjkdsalbfasl</p> -->
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
                <call-form></call-form>
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
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
    padding: 20px;
    justify-content: center;
    align-items: center;
    align-content: center;
    width: 80vw;
    align-items: center;
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