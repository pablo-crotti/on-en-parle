<template>
    <AppLayout title="On en parle | Contact">
        <ContactForm></ContactForm>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import ContactForm from "./contact-form.vue";

export default {
    components: {
        AppLayout,
        ContactForm,
    },
};
</script>
