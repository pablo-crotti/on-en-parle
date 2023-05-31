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
    <AppLayout :title="'On en parle | Émissions (Live)'">
        <div class="live-wrapper">

        </div>
    </AppLayout>
</template>