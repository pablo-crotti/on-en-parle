<template>
    <AppLayout title="On en parle | Émissions (Modification d'émission)">
        <div class="newprogram-wrapper">
            <modifyProgram />
        </div>
    </AppLayout>
</template>

<script>
/**
 *
 * Component: AppLayout:
 * Description: Layout component for the admin section.
 * Component: modifyProgram:
 * Description: A reusable component for modifying a program.
 */
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import modifyProgram from "@/Pages/Admin/Programs/Programs/modifyProgram.vue";

export default {
    components: {
        AppLayout,
        modifyProgram,
    },
};
</script>
