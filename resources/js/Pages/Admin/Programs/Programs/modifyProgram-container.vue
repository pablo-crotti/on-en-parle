<template>
    <AppLayout title="On en parle | Émissions (Modification d'émission)">
        <div class="newprogram-wrapper">
            <modifyProgram />
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
import modifyProgram from "@/Pages/Admin/Programs/Programs/modifyProgram.vue";

export default {
    components: {
        AppLayout,
        modifyProgram
    },
}
</script>