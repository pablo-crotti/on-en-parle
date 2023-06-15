<template>
    <div class="modal-overlay" v-if="isOpen">
        <div class="modal-content">
            <span class="modal-validation-title">{{ title }}</span>
            <p class="modal-validation-text">{{ message }}</p>
            <button class="modal-validation-btn" @click="closeModal">
                Fermer
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
        isOpen: {
            type: Boolean,
            required: true,
        },
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
    },
};
</script>
