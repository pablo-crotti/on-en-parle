<script>
    import AppLayout from '@/Layouts/AppLayoutUser.vue';
    import adminMessageContainer from '@/Pages/Admin/Reception/Inbox/adminMessageContainer.vue';
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
            <adminMessageContainer :no="1" :id="4"></adminMessageContainer>
        </div>
    </AppLayout>
</template>