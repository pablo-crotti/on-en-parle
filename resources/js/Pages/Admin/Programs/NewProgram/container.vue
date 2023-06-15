<template>
    <AppLayout title="On en parle | Émissions (Nouvelle émission)">
        <div class="newprogram-wrapper">
            <newProgram />
        </div>
    </AppLayout>
</template>

<script>
/**
 *Component: AppLayoutAdmin
 * Description: The main layout component for the admin section.
 *
 * Component: newProgram:
 * Description: A component for creating a new program.
 */
import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import newProgram from "./newProgram.vue";

export default {
    components: {
        AppLayout,
        newProgram,
    },
};
</script>
