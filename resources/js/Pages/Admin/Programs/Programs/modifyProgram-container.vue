<script>
import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
import axios from 'axios';
import modifyProgram from "@/Pages/Admin/Programs/Programs/modifyProgram.vue";

export default {
    components: {
        AppLayout,
        modifyProgram
    },

}
</script>

<template>
    <AppLayout title="On en parle | Émissions (Modification d'émission)">
        <div class="newprogram-wrapper">
            <modifyProgram/>
        </div>
    </AppLayout>
</template>
