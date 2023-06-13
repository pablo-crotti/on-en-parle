<script>
    import axios from 'axios';
    import ChatMessage from '@/Pages/MyComponents/ChatMessages.vue';
    import CallForm from '@/Pages/MyComponents/CallForm.vue';
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
  //  import ChatContainer from '@/Pages/MyComponents/admin-message.vue';


    export default {
        components: {
            CallForm,
            ChatMessage,
            AppLayout,
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
      statu: ["Inbox", "Présélectionnés", "Sélectionnés", "Régie", "","Prêt à diffuser"],
      couleurtitre: ["#FF0000", "red", "#008000","#FF9F00", "", "#0000FF"],
    chatroomId:null,
    calls: this.callChatroom,
    sortType: 'creation',
    };
  },

  methods: {
   
    drag(event, messageId) {
    event.dataTransfer.setData('text', messageId);
  },

  async drop(event, status) {
    const messageId = parseInt(event.dataTransfer.getData('text'));
      const message = this.messages.find(m => m.id === messageId);

      if (message) {if (status === 3 && !message.audio[0]) {
        event.preventDefault();
      } else {
        message.status = status;
        await axios.post(`/AdminInbox/message/${message.id}/update`, { status });
      }}
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
    async deleted(message) {
        try {
            await axios.post(`/AdminInbox/message/${message.id}/update`, { status: 10 });
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
    sortedByCreation() {
        return [...this.messages].sort((a, b) => {
            return new Date(b.created_at) - new Date(a.created_at); // Trier par date de création en ordre décroissant
        });
    },
    sortedByLikes() {
        return [...this.messages].sort((a, b) => b.nb_likes - a.nb_likes); // Trier par nombre de likes en ordre décroissant
    },
  
    sortedMessages() {
        if (this.sortType === 'creation') {
            return this.sortedByCreation;
        } else if (this.sortType === 'likes') {
            return this.sortedByLikes;
        }
    }},

    created() {
        this.messages = this.initialMessages;
        let convertedId = null;

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
    <AppLayout title="On en parle | Administration (Gestion)">
        <div class="containerManagement">

                <div id="boutonsmangament">
                <div id="boutonsmangamenttype">
                    <button @click="sortType = 'creation'" >Création</button>
            <button @click="sortType = 'likes'" >Like</button>
                </div>
                        <call-form
                        :room="chatroomId"></call-form>
                </div>
        <div class="columns">

            <div class="column" v-for="status in [0,1,2,3,5]" :key="status">
                <div class="admin-messages-container" >
                    <div class="admin-messages-title-container" :style="{backgroundColor: couleurtitre[status]}">
                        <div class="admin-messages-title">
                            {{ statu[status] }}</div>
                    </div>
                <div class="admin-messages-list"
                :id="`column-${status}`"
                        @drop="drop($event, status)"
                        @dragover.prevent>
                    
                        <div class="admin-messages-item" v-for="message in sortedMessages.filter(m => m.status === status)">
                            <chat-message
                            :key="message.id"
                            :message="message"
                            :calls="calls"
                            
                            :audiofiles="audiofiles"
                            @dragstart="drag($event, message.id)"
                            @modify="modifier"
                            @delete="deleted"
                            /> 
                        </div>
                
                    </div>
                </div>
                </div>
            </div>

        </div> 
                
    </AppLayout>
</template>


