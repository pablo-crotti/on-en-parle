<!-- composant enfant ChatMessage -->
<template>
<div  
    draggable="true" 
    @dragstart="$emit('dragstart', $event, message.id)" 
    class="message-wrapper"
>

  <div  v-if="isAudio"  class="message-header"  :style="{ backgroundColor:'#FBEF85'}">
              <!-- <div>
   <span class="symbol-header material-symbols-outlined">{{ headerSymbol }}</span> 
                <span v-if="message.call.length > 0" class="message-caller">{{message.call[0].caller}}</span>
            
            </div>  -->
                <span class="symbol-header material-symbols-outlined">mic</span>
  </div>
  
  
  <div v-else  onload="couleur(headercolors[1])" 
  class="message-header"  :style="{ backgroundColor:'#8239DF'}">
  <span class="symbol-header material-symbols-outlined">textsms</span>
</div>


<div class="message-body">

  <div v-if="isAudio" class="message-content">
    <p >
      <span class="audio-duration"> Durée du player</span>

     {{ audiofiles[0].audio_files[0] }}
     <!-- <audio controls @loadedmetadata="audioLoaded">
            <source :src="message.audio[0].audio_file" type="audio/mpeg">
               Your browser does not support the audio element.
      </audio> -->
    </p>

  </div>
    <div  v-else class="message-content" >
        <p v-if="!editing" class="message-text" >{{ message.content }}</p>
      <input v-else type="text" v-model="message.content" class="input" @keyup.enter="saveChanges"/>

  </diV>
  



  <div v-if="message.status !== 10 && message.status !== 5" class="message-actions">
        <span class="material-symbols-outlined" @click="editing = !editing">edit</span>
        <span class="material-symbols-outlined" @click="$emit('delete', message)">delete</span>
      </div>

  <div v-else-if="message.status === 10">
        <span class="material-symbols-outlined" @click="$emit('archive', message)">archive</span>
   </div>

</div>

  </div>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      default: () => ({}),
    },
    audiofiles: {
      type: Array,
      default: () => [],
    },
    
   
  },

  data() {
    return {
      headerColor:"",
      headercolors:[["audio","message"]],
      editing: false,

    };
  },

  methods: {
    saveChanges() {
      this.$emit('modify', this.message);
      this.editing = false;
    },
    filterMessages() {  // Nouvelle fonction pour filtrer les messages
                this.filteredMessages = this.messages.filter(message => {
                    return !this.audiofiles.some(audiofile => audiofile.id === message.id);
                });
            },

            audioLoaded(event) {
            const audio = event.target;
            const duration = Math.floor(audio.duration);
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            this.audioDuration = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        },

        verif(){
          console.log("verif")
        },

        couleur(type){
      if (type==="message") {
        console.log("c'est un message")
            this.headerColor = '#8239DF';
         
        } else if (type==="audio") {
            this.headerColor = '#FBEF85';
            console.log("c'est un audio")

        } 
        console.log("icibas")
        console.log(this.headerColor)
        // else if (message.call.length > 0) {
        //     headerColor = '#FC9E5A';
        //     headerSymbol = 'call';
        // }
    }
  },
  

  computed: {
    isAudio() {
      return this.audiofiles.some(audio => audio.id === this.message.id);
    },
    audioFile() {
      console.log(this.audiofiles);

      const audio = this.audiofiles.find(audio => audio.id === this.message.id);
      return audio ? audio.audio_files[0] : null;
    },

  },
  created() {
   
   this.couleur(this.headercolors[1])
  },
};
</script>
