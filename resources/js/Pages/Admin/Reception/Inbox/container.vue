<script>
    import axios from 'axios';
    import ChatMessage from '@/Pages/MyComponents/ChatMessages.vue';
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';


    export default {
        components: {
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
        type: String,
        required: true
    },

   
  },

  data() {
    return {
      messages: this.initialMessages,
      audiofiles: this.audioChatroom,
      filteredMessages: [],
      statu: ["Inbox", "Présélectionnés", "Sélectionnés", "Régie", "Prêt à diffuser"],
      sortType: 'creation', // Ajouté

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
    async deleted(message) {
      let status = 10;
        try {
          await axios.post(`/AdminInbox/message/${message.id}/update`, { status });
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
            console.log("Création")
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
    }

}
};

</script>

<template>
  <AppLayout title="On en parle | Réception (Inbox)">
    <div class="containerInbox">

      <div id="boutonsmangament">
          
        <div id="boutonsmangamenttype">
            <button @click="sortType = 'creation'" >Date</button>
            <button @click="sortType = 'likes'" >Like</button>
        </div>
          </div>
        
    <div class="columns">

        <div class="column" v-for="status in [0, 1]" :key="status">
          <div class="admin-messages-container" style="width:30vw; margin-right:30px;">
         
            <div class="admin-messages-title-container">
              
              <div class="admin-messages-title">{{ statu[status] }}</div>
              </div>
            <div class="admin-messages-list "
              :id="`column-${status}`"
                      @drop="drop($event, status)"
                      @dragover.prevent>
                
                      <div class="admin-messages-item" v-for="message in sortedMessages.filter(m => m.status === status)">
                    
                            <chat-message
                          :key="message.id"
                          :message="message"
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
