<script>
    import AppLayout from '@/Layouts/AppLayoutUser.vue';
    import adminMessageContainer from '@/Pages/MyComponents/adminMessageContainer.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
            adminMessageContainer
        },
    }
</script>

<template>
    <AppLayout title="On en parle | Administration (Gestion)">
        <div class="management-wrapper">
            <adminMessageContainer :no="1" :id="0"></adminMessageContainer>
            <adminMessageContainer :no="1" :id="1"></adminMessageContainer>
            <adminMessageContainer :no="1" :id="2"></adminMessageContainer>
            <adminMessageContainer :no="1" :id="3"></adminMessageContainer>
            <adminMessageContainer :no="1" :id="4"></adminMessageContainer>
        </div>
    </AppLayout>
</template>