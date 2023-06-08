<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import adminMessageContainer from '@/Pages/MyComponents/adminMessageContainer.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
            adminMessageContainer
        },
        data: function () {
        return {
            idProgram: window.location.href.split('/').pop(),
        }
    },
    }
</script>

<template>
    <AppLayout title="On en parle | Réception (Inbox)">
        <div class="inbox-wrapper">
            <adminMessageContainer :no="idProgram" :id="0"></adminMessageContainer>
            <adminMessageContainer :no="idProgram" :id="1"></adminMessageContainer>
        </div>
    </AppLayout>
</template>