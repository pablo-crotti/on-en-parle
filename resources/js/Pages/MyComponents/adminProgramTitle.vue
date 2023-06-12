<script>
    import axios from 'axios';
    import moment from 'moment';
    import 'moment/dist/locale/fr';

    export default {
        data() {
            return {
                programTitle: "",
                currentURL: window.location.href,
                currentId: this.currentURL.split('/').pop(),
            }
        },
        methods: {
            getProgramTitle(programId) {
                axios.get('/chat/room/' + programId)
                    .then(response => {
                        moment.locale('fr');
                        this.programTitle = `(${moment(response.data.broadcast_date).format("D.MM.YYYY")}) ${response.data.title}`;
                        return this.programTitle;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        created() {
            this.getProgramTitle(this.currentId)
        }
    }
    
</script>

<template>
    <div>
        {{ this.programTitle }}
    </div>
</template>