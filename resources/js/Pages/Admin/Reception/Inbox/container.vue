<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
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
    <AppLayout title="On en parle | Réception (Inbox)">
        <div class="inbox-wrapper">
            <adminMessageContainer :no="1" :id="0"></adminMessageContainer>
            <adminMessageContainer :no="1" :id="1"></adminMessageContainer>
        </div>
    </AppLayout>
</template>