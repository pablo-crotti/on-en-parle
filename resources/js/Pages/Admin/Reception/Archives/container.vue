<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import archiveList from './archiveList.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
            archiveList
        },
    }
    
</script>

<template>
    <AppLayout title="On en parle | Réception (Archives)">
        <div class="archives-wrapper">
            <archiveList :no="1" :id="10"></archiveList>
        </div>
    </AppLayout>
</template>