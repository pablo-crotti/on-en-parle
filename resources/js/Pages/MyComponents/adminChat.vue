<template>
    <div class="admin-chat-container">
        <div class="admin-messages-container">

        </div>
        <div class="admin-message-input-container">
            <adminMessageInput />
        </div>
    </div>
</template>
<script>
    import adminMessageInput from "@/Pages/MyComponents/adminMessageInput.vue";
    export default {
        components: {
            adminMessageInput
        },
    }
</script>