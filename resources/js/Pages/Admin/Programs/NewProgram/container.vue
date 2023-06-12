<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import newProgram from './newProgram.vue';

    export default {
        components: {
            AppLayout,
            newProgram
        },
    }
</script>

<template>
    <AppLayout title="On en parle | Émissions (Nouvelle émission)">
        <div class="newprogram-wrapper">
                <newProgram />
        </div>
    </AppLayout>
</template>