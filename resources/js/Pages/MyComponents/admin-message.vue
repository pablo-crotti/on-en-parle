<script>
import { ref } from 'vue';

export default {
    props: ['message'],
    data() {
        return {

        };
    },
    methods: {
        getFormattedDate(message) {
            const dateString = message.updated_at; // Utilisez ici votre propre chaîne de date
            const date = new Date(dateString);

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const formattedDate = `${day}/${month}/${year}`;

            return formattedDate;
        }
    },

    setup(props) {



        const message = props.message;

        console.log(message);

        let headerColor = "";
        let headerSymbol = "";

        if (message.text.length > 0) {
            headerColor = '#8239DF';
            headerSymbol = 'textsms';
        } else if (message.audio.length > 0) {
            headerColor = '#FBEF85';
            headerSymbol = 'mic';
        } else if (message.call.length > 0) {
            headerColor = '#FC9E5A';
            headerSymbol = 'call';
        }





        return {
            headerColor,
            headerSymbol
        };
    },
};
</script>



<template>
    <div class="message-wrapper">

        <div class="message-header" :style="{ backgroundColor: headerColor }">
            <span class="symbol-header material-symbols-outlined">{{ headerSymbol }}</span>
            <span v-if="message.audio.length > 0" class="audio-duration">00:00</span>
            <span v-if="message.call.length > 0" class="message-caller">{{message.call[0].caller}}</span>
        </div>

        <div class="message-body">
            <span class="message-date">{{ getFormattedDate(message) }}</span>

            <div class="message-content">

                
                 <p class="message-text" v-if="message.text.length > 0">
                    {{ 'dsadqsdqsdqswdqwdqdwqdqwqqddwdqdqwwqdaqdwqdqdwdqwdwdwdqw' }}
                </p>

                <div v-if="message.audio.length > 0">
                    <audio controls>
                        <source :src="message.audio[0].audio_file" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>

                <div v-if="!message.call.length > 0" class="message-actions">
                    <span class="material-symbols-outlined">edit</span>
                    <span class="material-symbols-outlined">delete</span>
                </div>
                
            </div>
                    

            <div v-if="message.nb_likes > 0" class="message-likes">
                <span class="nb-likes">{{ message.nb_likes }}</span>
                <span class="message-like-symbol material-symbols-outlined">favorite</span>
            </div>
        </div>
    </div>
</template>

<style>
.container {
    padding: 10px;
}
</style>