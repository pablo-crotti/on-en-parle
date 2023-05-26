<template>
    
        <div class="input-message-container">
            <input 
                id="input-message"
                data-display="true"
                type="text"
                v-model="message"
                @keyup.enter="sendMessage()"
                @keyup="handleKeyUp()"
                placeholder="Taper un message...">
            <div class="audio-recorder" id="audio-recorder" data-display="false">
                <p>Test</p>
            </div>
            <div id="delete" data-display="false" class="input-message-button">
                <button
                    class="">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </div>
            <div id="record-message" data-display="true" class="input-message-button">
                <button
                    class=""
                    @click="recordMessage()">
                    <span class="material-symbols-outlined">mic</span>
                </button>
            </div>
            <div id="send-message" data-display="false" class="input-message-button">
                <button
                    @click="sendMessage()"
                    class="">
                    <span class="material-symbols-outlined">send</span>
                </button>
            </div>
            <div id="call" data-display="true" class="input-message-button">
                <button
                    class="">
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
                message: ''
            }
        },
        methods: {
            handleKeyUp() {
                if (this.message) {
                    const sendMessage = document.getElementById('send-message')
                        .dataset.display = 'true'
                    const recordMessage = document.getElementById('record-message')
                        .dataset.display = 'false'
                } else {
                    const sendMessage = document.getElementById('send-message')
                        .dataset.display = 'false'
                    const recordMessage = document.getElementById('record-message')
                        .dataset.display = 'true'
                }
            },
            sendMessage() {
                if( this.message == ' ') {
                    return
                }
                axios.post('/chat/room/' + this.room + '/message', {
                    message: this.message
                })
                .then(response => {
                    if (response.status == 201) {
                        this.message = ''
                        this.$emit('messagesent')
                    }
                })
                .catch(error => {
                    console.log(error)
                })
            },
            recordMessage() {
                const call = document.getElementById('call')
                    .dataset.display = 'false'
                const sendMessage = document.getElementById('send-message')
                    .dataset.display = 'true'
                const input = document.getElementById('input-message')
                    .dataset.display = 'false'
                const recorder = document.getElementById('audio-recorder')
                    .dataset.display = 'true'
                const recordMessage = document.getElementById('record-message')
                    .dataset.display = 'false'
                const del = document.getElementById('delete')
                    .dataset.display = 'true'
            }
        }  
    }

    
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