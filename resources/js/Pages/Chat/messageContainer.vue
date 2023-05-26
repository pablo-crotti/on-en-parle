<script>
    import MessageItem from './messageItem.vue';

    export default {
        components: {
            MessageItem
        },
        props: ['messages'],
        
    }
</script>
<template>
        <div class="message-container">
            <div class="message-item" v-for="(message, index) in messages" :key="index">
                <message-item :message="message"/>
            </div>
        </div>
</template>

