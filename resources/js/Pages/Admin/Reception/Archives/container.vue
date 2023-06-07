<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import archiveList from './archiveList.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
            archiveList
        },
        data: function ()  {                
            return {
                idProgram: window.location.href.split('/').pop(),
            }
        },
    }
    
</script>

<template>
    <app-layout title="On en parle | Réception (Archives)">
        <div class="archives-wrapper">
            <archiveList :no="idProgram" :id="10"></archiveList>
        </div>
    </app-layout>
</template>