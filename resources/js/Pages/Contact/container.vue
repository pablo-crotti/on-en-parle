<template>
    <AppLayout title="On en parle | Contact">
        <ContactForm></ContactForm>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: The layout component for the application.
 * Component: ContactForm
 * Description: The component for the contact form.
 */
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import ContactForm from "./contact-form.vue";

export default {
    components: {
        AppLayout,
        ContactForm,
    },
};
</script>
