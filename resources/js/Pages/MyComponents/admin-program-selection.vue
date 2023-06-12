<script>
    import axios from 'axios';
    import moment from 'moment';
    import 'moment/dist/locale/fr';

    export default {
        props: {
            program: {
                type: Object
                },
            currentURL: "",   
        },
        data() {
            return {
                room: {
                    type: Object
                }
            }
        },
        methods: {
            formatDate(date) {
                moment.locale('fr');
                return [moment(date).format('dddd'), moment(date).format('D MMMM YYYY')];
            },
            setCurrentProgram(newId) {
                let regex = /\d+$/;
                let newURL = this.currentURL.replace(regex, newId);
                window.location.replace(newURL);
            },
        },
        created() {
            axios.get('/chat/rooms-list').then(response => {
                this.room = response.data.find(item => item.id === this.program.id);
            })
        }
    }
</script>

<template>
    <div class="program-item" @click="setCurrentProgram(program.id)">
        <div class="program-header">
            <img class="program-cover" :src="program.image"/>
            <div class="program-date-text">
                {{ this.formatDate(program.broadcast_date)[0] }}
                <br>
                {{ this.formatDate(program.broadcast_date)[1] }}
            </div>
        </div>
        <div class="program-info">
            <span class="program-title">{{ program.title }}</span>
            <div class="program-interactions" >
                <span class="material-symbols-outlined">
                    forum
                </span>
                <span>{{ room.messages_count }}</span>   
            </div>                        
        </div>
    </div>  
</template>