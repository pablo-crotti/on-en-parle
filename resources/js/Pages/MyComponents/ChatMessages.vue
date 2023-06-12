<!-- composant enfant ChatMessage -->
<template>
  <div  
      draggable="true" 
      @dragstart="$emit('dragstart', $event, message.id)" 
      class="message-wrapper"
  >
  
  <div v-if="isCall" class="message-header" :style="{ backgroundColor:'#FC9E5A'}">
    <span class="symbol-header material-symbols-outlined">call</span>
    <span class="message-caller">{{ this.message.call[0].caller}}</span>
  </div>
  
  <div v-else-if="isAudio" class="message-header" :style="{ backgroundColor:'#FBEF85'}">
                <!-- <div>
     <span class="symbol-header material-symbols-outlined">{{ headerSymbol }}</span> 
                  <span v-if="message.call.length > 0" class="message-caller">{{message.call[0].caller}}</span>
              
              </div>  -->
                  <span class="symbol-header material-symbols-outlined">mic</span>
    </div>
    
    
    <div v-else-if="isText" class="message-header" :style="{ backgroundColor:'#8239DF'}">
    <span class="symbol-header material-symbols-outlined">textsms</span>
  </div>
  
  
  <div class="message-body">
   
    <div v-if="isAudio && !isCall" class="message-content">
      <p >
        <span class="audio-duration"> Audio Durée du player</span>
  
       {{ audiofiles[0].audio_files[0] }}
       <audio controls @loadedmetadata="audioLoaded">
              <source :src="getAudioPath(message.audio[0].audio_file)" type="audio/mpeg">
                 Your browser does not support the audio element.
        </audio>
      </p>
  
    </div>

      <div  v-else class="message-content" >
          <p v-if="!editing" class="message-text" >{{ message.content }}</p>
        <input v-else type="text" v-model="message.content" class="input" @keyup.enter="saveChanges"/>
  
    </diV>
    
  
  
  
    <div v-if="message.status !== 10 && message.status !== 5" class="message-actions">
      <div v-if="isText || isAudio">
      <span   class="material-symbols-outlined">Favorite</span> <p>{{ message.nb_likes}}</p>
    </div>
      <span class="material-symbols-outlined" @click="editing = !editing">edit</span>
          <span class="material-symbols-outlined" @click="$emit('delete', message)">archive</span>
        </div>
  
    <div v-else-if="message.status === 10">
          <span class="material-symbols-outlined" @click="$emit('archive', message)">delete</span>

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
      
      calls: {
        type: Array,
        default: () => [],
      },
    },
  
    data() {
      return {
        headerColor:"",
        headercolors:[["audio","message","call"]],
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
  
          couleur(type){
        if (type==="message") {
          console.log("c'est un message")
              this.headerColor = '#8239DF';
           
          } else if (type==="audio") {
              this.headerColor = '#FBEF85';
              console.log("c'est un audio")
  
          } 
          else if (type==="call") {
              headerColor = '#FC9E5A';
              headerSymbol = 'call';
          }
      },
      getAudioPath(audioFile) {
        return `${window.location.origin}/storage/rec/${audioFile}`
      },
    },
    
  
    computed: {
      isAudio() {
        return this.message.audio && this.message.audio.length > 0;
      },
      isCall() {
        return this.message.call && this.message.call.length > 0;
      },
    isText() {
      return (!this.message.audio || this.message.audio.length === 0) && (!this.message.call || this.message.call.length === 0);
    },
      audioFile() {
       
  
  
        const audio = this.audiofiles.find(audio => audio.id === this.message.id);
        return audio ? audio.audio_files[0] : null;
      },
      
  
    },
    created() {
  
     this.couleur(this.headercolors[1])
    },
  
  
  }
  </script>
  