<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';



    export default {
        components: {
            AppLayout
        },
        data() {
            return {
                program: null,
                background: ''
            }
        },
        methods : {
            goLive() {
                    axios.post(`/emission/${this.program.id}/live`).then(response => {
                        this.program = response.data.chatRoom;
                        this.isLive();
                    
                });
            },
            isLive() {
            
                if (this.program.on_air) {
                    this.background = '#838383';
                } else {
                    this.background = '#f1f1f1';
                }
            }
        },
        created() {
            axios.get('/prochaine-emission').then(response => {
                
                this.program = response.data;
                this.isLive(this.program);
            });

            
        }
    }
</script>

<template>
    <AppLayout title="On en parle | Émissions (Live)">
        <div class="live-wrapper">
            <div class="live-content-wrapper">
                <h1 class="live-title">Émissions (Live)</h1>
                <h2 class="live-subtitle">{{ this.program.title }} </h2>
                <span class="live-date">Emission du {{ this.program.broadcast_date }}</span>
            </div>

            <div class="golive-wrapper">
                <div class="golive-header">
                    <span class="golive-symbol material-symbols-outlined">videocam    </span>
                </div>

                <div class="golive-body">
                    <div class="golive-buttons">
                    <div class="golive-button" :style="{ backgroundColor: this.background }" @click="goLive()">
                        <div class="golive-rec-symbol"></div>
                        Démarrer le live
                        </div>
                    </div> 
                </div>
                
            </div>
            
        </div>
    </AppLayout>
</template>

<style>

</style>