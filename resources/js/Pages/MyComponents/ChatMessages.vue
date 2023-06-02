<template>
  <div>
    <div
      v-for="message in messages"
      :key="message.id"
      class="message"
      :id="`message-${message.id}`"
      draggable="true"
      @dragstart="$emit('dragstart', $event, message.id)"
      v-if="message && !audioIds.includes(message.id)"
    >
   
      <div class="message-header" :style="{ backgroundColor: headerColor }">
        <span class="symbol-header material-symbols-outlined">{{ headerSymbol }}</span>
      </div>
      <p>{{ message.content }}</p>
            <div class="message-body">
        <div class="buttons">
          <button class="material" @click="editing = !editing">Modifier</button>
          <button class="material" @click="$emit('delete', message)">Effacer</button>
        </div>
      </div>
    </div>

    
    <div
      v-for="audio in audiofiles"
      :key="audio.chat_message_id"
      class="message"
      :id="`message-${audio.chat_message_id}`"
      draggable="true"
      @dragstart="$emit('dragstart', $event, audio.chat_message_id)"
    >
      <div class="message-header" :style="{ backgroundColor: headerColor }">
        <div>
          <span class="symbol-header material-symbols-outlined">{{ headerSymbol }}</span>
          <div>
            <audio controls>
              <source :src="audio.audio_file" type="audio/mpeg">
              Your browser does not support the audio element.
            </audio>
          </div>
        </div>
      </div>

      <div class="message-body">
        <div class="buttons">
          <button class="material" @click="editing = !editing">Modifier</button>
          <button class="material" @click="$emit('delete', audio)">Effacer</button>
        </div>
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
      editing: false,
    };
  },
  computed: {
    audioIds() {
      return this.audiofiles.map(audio => audio.chat_message_id);
    },

    headerColor() {
      // Calculate the header color
    },
    headerSymbol() {
      // Determine the header symbol
    },
  },

  created(){
    console.log("messages")

    console.log(this.messages)
  }
};

</script>