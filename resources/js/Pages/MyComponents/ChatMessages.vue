<template>    
  <div 
    class="message"
    :id="`message-${message.id}`"
    draggable="true"
    @dragstart="$emit('dragstart', $event, message.id)"
  >
    <div class="message-header">
      <p>{{ message.content }}</p>
      <input type="text" v-model="message.content" class="input" @keyup.enter="$emit('modify', message)">
    </div>
    <div class="buttons">
      <button class="button" @click="editing = !editing">Modifier</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: Object
  },
  data() {
  return {
    editing: false
  };
},

}
</script>
