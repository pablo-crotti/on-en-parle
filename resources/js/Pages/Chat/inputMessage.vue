<template>
    <div class="input-message-container">
      <input
        id="input-message"
        data-display="true"
        type="text"
        v-model="message"
        @keyup.enter="sendMessage()"
        @keyup="handleKeyUp()"
        placeholder="Taper un message..."
      />
      <div class="audio-recorder" id="audio-recorder" data-display="false">
        <div class="recording-infos-container">
          <p>{{ recordingTime }} / 60 sec.</p>
        </div>
      </div>
      <div id="delete" data-display="false" class="input-message-button">
        <button class="" @click="deleteRecord()">
          <span class="material-symbols-outlined">delete</span>
        </button>
      </div>
      <div id="record-message" data-display="true" class="input-message-button">
        <button class="" @click="recordMessage()">
          <span class="material-symbols-outlined">mic</span>
        </button>
      </div>
      <div id="send-message" data-display="false" class="input-message-button">
        <button @click="sendMessage()" class="">
          <span class="material-symbols-outlined">send</span>
        </button>
      </div>
      <div id="send-record" data-display="false" class="input-message-button">
        <button @click="sendRecord()" class="">
          <span class="material-symbols-outlined">send</span>
        </button>
      </div>
      <div id="call" data-display="true" class="input-message-button">
        <button class="">
          <span class="material-symbols-outlined">call</span>
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: ['room'],
    data: function () {
      return {
        message: '',
        mediaRecorder: null,
        chunks: [],
        recordingActive: false,
        recordingTime: 0,
        timerInterval: null,
        audioDeleted: false,
      };
    },
    methods: {
      handleKeyUp() {
        if (this.message) {
          const sendMessage = document.getElementById('send-message').dataset.display = 'true';
          const recordMessage = document.getElementById('record-message').dataset.display = 'false';
        } else {
          const sendMessage = document.getElementById('send-message').dataset.display = 'false';
          const recordMessage = document.getElementById('record-message').dataset.display = 'true';
        }
      },
      sendMessage() {
        if (this.message.trim() === '') {
          return;
        }
        axios
          .post('/chat/room/' + this.room + '/message', {
            message: this.message,
          })
          .then((response) => {
            if (response.status == 201) {
              this.message = '';
              this.$emit('messagesent');
            }
          })
          .catch((error) => {
            console.log(error);
          });
      },
      recordMessage() {
        const call = document.getElementById('call').dataset.display = 'false';
        const sendMessage = document.getElementById('send-message').dataset.display = 'false';
        const sendRecord = document.getElementById('send-record').dataset.display = 'true';
        const input = document.getElementById('input-message').dataset.display = 'false';
        const recorder = document.getElementById('audio-recorder').dataset.display = 'true';
        const recordMessage = document.getElementById('record-message').dataset.display = 'false';
        const del = document.getElementById('delete').dataset.display = 'true';
  
        this.startRecording();
      },
      deleteRecord() {
        const call = document.getElementById('call').dataset.display = 'true';
        const sendMessage = document.getElementById('send-message').dataset.display = 'false';
        const sendRecord = document.getElementById('send-record').dataset.display = 'false';
        const input = document.getElementById('input-message').dataset.display = 'true';
        const recorder = document.getElementById('audio-recorder').dataset.display = 'false';
        const recordMessage = document.getElementById('record-message').dataset.display = 'true';
        const del = document.getElementById('delete').dataset.display = 'false';

        this.audioDeleted = true;
  
        this.stopRecording();
        this.resetRecording();
      },
      sendRecord() {

        this.stopRecording();        

        const call = document.getElementById('call').dataset.display = 'true';
        const sendMessage = document.getElementById('send-message').dataset.display = 'false';
        const sendRecord = document.getElementById('send-record').dataset.display = 'false';
        const input = document.getElementById('input-message').dataset.display = 'true';
        const recorder = document.getElementById('audio-recorder').dataset.display = 'false';
        const recordMessage = document.getElementById('record-message').dataset.display = 'true';
        const del = document.getElementById('delete').dataset.display = 'false';

      },
      startRecording() {
        this.audioDeleted = false;
        navigator.getUserMedia =
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia;
  
        navigator.mediaDevices.getUserMedia({ audio: true })
          .then((stream) => {
            this.mediaRecorder = new MediaRecorder(stream);
            this.mediaRecorder.addEventListener('dataavailable', (event) => {
                this.chunks.push(event.data);
                if (!this.audioDeleted) {   
                    const audioBlob = new Blob(this.chunks, { type: 'audio/webm' });
                    const formData = new FormData();
                    formData.append('audio', audioBlob);
            
                    axios
                    .post('/store-audio/' + this.room, formData)
                    .then((response) => {
                        console.log('Enregistrement envoyé:', response.data.path);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                    this.resetRecording();
                }
            });
            this.mediaRecorder.start();
            this.recordingActive = true;
  
            this.timerInterval = setInterval(() => {
              this.recordingTime++;
            }, 1000);
  
          },
          (error) => {
            console.error(error);
          }
        );
      },
      stopRecording() {
        if (this.mediaRecorder && this.recordingActive) {
          this.mediaRecorder.stop();
          this.recordingActive = false;
  
          clearInterval(this.timerInterval);
        }
      },
      resetRecording() {
        this.mediaRecorder = null;
        this.chunks = [];
        this.recordingTime = 0;
      },
    },
  };
  </script>
  
  <style>
  div.input-message-container input:focus,
  div.input-message-container input:active,
  div.input-message-container input:focus-visible,
  [type="text"]:focus,
  [type="email"]:focus,
  [type="url"]:focus,
  [type="password"]:focus,
  [type="number"]:focus,
  [type="date"]:focus,
  [type="datetime-local"]:focus,
  [type="month"]:focus,
  [type="search"]:focus,
  [type="tel"]:focus,
  [type="time"]:focus,
  [type="week"]:focus,
  [multiple]:focus,
  textarea:focus,
  select:focus {
    outline: none !important;
    border: none !important;
  }
  </style>
  