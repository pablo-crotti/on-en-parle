<script>
    import AppLayout from '@/Layouts/AppLayoutUser.vue';
    import adminMessageContainer from './adminMessageContainer.vue';
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
            <adminMessageContainer :no="1" :id="1"></adminMessageContainer>
        </div>
    </AppLayout>
</template>