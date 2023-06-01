<template>
    <div class="day-container" :class="{ current: current }">
        <button v-if="hasTransmission" :id="'transmission-'+ hasTransmission" @click="showTransmission(hasTransmission)">
            <p class="hasTransmission">{{day}}</p>
        </button>
        <p v-else >{{day}}</p>
    </div>
</template>

<script>

    export default {
        props: ['day', 'current', 'month', 'year', 'hasTransmission' ],
        methods: {
            showTransmission(transmissionId) {
                this.$emit('transmission-click', transmissionId);
            }
        }
    }

</script>