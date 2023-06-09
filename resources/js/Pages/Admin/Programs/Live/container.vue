<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';
    import modalConfirmation from '@/Pages/MyComponents/modalConfirmation.vue';

    export default {
        components: {
            AppLayout,
            modalConfirmation
        },
        data() {
            return {
                program: {},
                background: '',
                btnCaption: '',
                isModalOpen: false,
                modalTitle: 'Confirmation',
                modalMessage: 'Êtes-vous sûr de vouloir démarrer le Live ?'
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
                    this.btnCaption = 'Arrêter le live';
                    this.modalMessage = 'Êtes-vous sûr de vouloir arrêter le Live ?';
                    return true;
                } else {
                    this.background = '#f1f1f1';
                    this.btnCaption = 'Démarrer le live';
                    this.modalMessage = 'Êtes-vous sûr de vouloir démarrer le Live ?';
                    return false;
                }
            },
            
            openModal() {
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
            }
        },
        created() {
            axios.get('/prochaine-emission').then(response => {
                this.program = response.data;
                if (this.program !== null) {
                    this.isLive(this.program);
                }
            });
        }

    }
</script>

<template>
    <AppLayout title="On en parle | Émissions (Live)">
        <modalConfirmation :title="modalTitle" :message="modalMessage" :is-open="isModalOpen" @close="closeModal" @validate="goLive" />
        <!-- <Modal></Modal> -->
        <div class="live-wrapper">
            <div class="live-content-wrapper">
                <h1 class="live-title">Émission (Live)</h1>
                <h2 class="live-subtitle">{{ this.program.title }} </h2>
                <span class="live-date">Emission du {{ this.program.broadcast_date }}</span>
            </div>

            <div class="golive-wrapper">
                <div class="golive-header">
                    <span class="golive-symbol material-symbols-outlined">videocam    </span>
                </div>

                <div class="golive-body">
                    <div class="golive-buttons">
                    <div class="golive-button" @click="openModal" :style="{ backgroundColor: this.background }" >
                        <div class="golive-rec-symbol"></div>
                            {{ this.btnCaption }}
                        </div>
                    </div> 
                </div>
                
            </div>
            
        </div>
    </AppLayout>
</template>