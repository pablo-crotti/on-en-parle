<template>
    <div 
      class="box"
      :id="id"
      @drop="drop"
      @dragover.prevent
      @dragleave="leave"
    >
      <slot></slot>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: ['id'],
  
    methods: {
      allowDrop(ev) {
        ev.preventDefault();
        ev.target.style.borderColor = "red";
      },
  
      leave(ev) {
        ev.target.style.borderColor = "black";
      },
      
      async drop(ev) {
        ev.preventDefault();
        let data = ev.dataTransfer.getData('text');
        ev.target.appendChild(document.getElementById(data));
        ev.target.style.borderColor = "black";
  
        const messageId = document.getElementById(data).getAttribute('data-message-id');
  
        // Mettre à jour le statut du message
        await axios.post(`/chat/${messageId}/update`, { status: this.id });
  
        alert("Dropped : " + document.getElementById(data).querySelector('p').innerHTML + " on " + ev.target.id);
      }
    },
  
    mounted() {
      let el = this.$el;
      el.addEventListener('dragover', this.allowDrop);
    },
  
    beforeUnmount() {
      let el = this.$el;
      el.removeEventListener('dragover', this.allowDrop);
    }
  }
  </script>