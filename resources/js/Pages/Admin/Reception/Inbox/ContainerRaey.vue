<script>
    import axios from 'axios';
    import ChatMessage from '@/Pages/MyComponents/ChatMessages.vue';
    import CallForm from '@/Pages/MyComponents/CallForm.vue'


    export default {
        components: {
            CallForm,
            ChatMessage
        },
    
    props: {
    initialMessages:{
      type: Array,
      required: true
    } 
  },

  data() {
    return {
      messages: this.initialMessages
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
    }

    }}

</script>

<template>
        <h1>ChatRecu</h1>
  <div class="container">
  
      <div class="column" v-for="status in [0, 1]" :key="status">
        <div 
            class="dropzone" :id="`column-${status}`"
            @drop="drop($event, status)"
            @dragover.prevent
        >
        <chat-message
        v-for="message in messages.filter(m => m.status === status)"
        :key="message.id"
        :message="message"
        @dragstart="drag($event, message.id)"
        @modify="modifier"
        @delete="deleteMessage"

        />
        </div>
      </div>
      <div id="app">
      <call-form></call-form>
    </div>
    </div>
</template>

<style>
h1 {
    font-size: 40px;
    text-align: center;
    color: white;
}
.container {
    width: 100%;
    height: 900px;
    display: flex;
    flex-direction: row;
    padding: 20px;
    justify-content: center;
    align-items: center;
    align-content: center;

    align-items: center;
    background-color: aliceblue;
}
.column {
    width: 50%;
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
</style>