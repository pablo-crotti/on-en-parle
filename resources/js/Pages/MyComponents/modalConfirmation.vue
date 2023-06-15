<template>
    <div class="modal-overlay" v-if="isOpen">
        <div class="modal-content">
            <span class="modal-validation-title">{{ title }}</span>
            <p class="modal-validation-text">{{ message }}</p>

            <div class="modal-selection-btn">
                <button class="modal-red-btn" @click="validateModal">
                    Confirmer
                </button>
                <button class="modal-grey-btn" @click="closeModal">
                    Fermer
                </button>
            </div>
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
        validateModal() {
            this.$emit("validate");
            this.closeModal();
        },
    },
};
</script>
