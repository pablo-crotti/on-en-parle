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
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import dropdownFilter from '@/Pages/MyComponents/dropdownFilter.vue';
  //  import ChatContainer from '@/Pages/MyComponents/admin-message.vue';


    export default {
        components: {
            ChatMessage,
            AppLayout,
            dropdownFilter
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
        categories: [
            {
                name: 'Date de création',
                value: 'creation'
            },
            {
                name: 'Nombre de likes',
                value: 'likes'
            }
        ]
    };
},

  methods: {
      handleFilterApplied(filterData) {
          this.sortType = filterData;
      },

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

                hideMessage(message) {
                    this.messages = this.messages.filter(m => m.id !== message.id);
    },

    },
    computed: {
        affichedon(){
        },
        audioMessages() {
            return this.messages.filter(m => m.audio && m.audio.length > 0 && m.status === 5 && m.call.length === 0);
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
    <AppLayout title="On en parle | Administration (Animateur)">

        <div class="containerManagement">

            <div id="boutonsmangament">
                <div id="boutonsmangamenttype">

<!--                    <button @click="sortType = 'creation'" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Création</button>
            <button @click="sortType = 'likes'" style="margin-right:15px; padding:10px;background-color: rebeccapurple;">Like</button>-->
                    <dropdownFilter :categories="categories" @filter-applied="handleFilterApplied"></dropdownFilter>
        </div>
       </div>
                <div class="columns">
            <div class="column" v-for="status in [0,1,2,3]" :key="status">
                <div class="admin-messages-container">
                    <div class="admin-messages-title-container">
                        <div class="admin-messages-title">{{ ['Messages Vocaux', 'Appels en attente', 'Messages', 'Diffusé'][status] }}</div>
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
                                    isInAnimator
                                    @cacher="hideMessage"
                                    :audiofiles="audiofiles"
                                    @dragstart="drag($event, message.id)"
                                    @modify="modifier"
                                    @archive="deleteMessage"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </AppLayout>
</template>


