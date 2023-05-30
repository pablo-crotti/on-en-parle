<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout
        },
    }
</script>

<template>
    <AppLayout :title="'On en parle | Réception (Inbox)'">
        <div class="inbox-wrapper">
            
        </div>
    </AppLayout>
</template>