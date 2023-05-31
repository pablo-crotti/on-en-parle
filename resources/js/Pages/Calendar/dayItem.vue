<template>
    <div class="day-container" :class="{ current: current }">
        <p>{{ hasTransmission }}</p>
        <p :class="{ hasTransmission: hasTransmission }">{{day}}</p>
        <!-- <p>{{  day }}</p> -->
        
    </div>

    

</template>

<script>

    export default {
        props: ['day', 'current', 'month', 'year', 'hasTransmission' ],
        methods: {
        
            
        },
        created() {
            
            // this.hasTransmission(this.day)
        }
        
    }

</script>