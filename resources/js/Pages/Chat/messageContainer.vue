<script>
    import MessageItem from './messageItem.vue';

    export default {
        components: {
            MessageItem
        },
        props: ['messages'],
        data() {
    return {
            filteredMessages: [], 
            filterBy: '', 
        };
    },
    methods: {
        filterMessages() {
            if (this.filterBy === 'date') {
                this.filteredMessages = [...this.messages]
            } else if (this.filterBy === 'likes') {
                this.filteredMessages = [...this.messages].sort(
                (a, b) => b.nb_likes - a.nb_likes
            );
            } else {
                this.filteredMessages = [...this.messages];
            }
        },
    },
    watch: {
        filterBy() {
            this.filterMessages(); 
        },
        messages() {
            this.filterMessages(); 
        },
    },
    created() {
        this.filterMessages(); 
    }
        
    }
</script>
<template>
        <div class="message-container">
            <h1>Vos questions</h1>
            <div class="filter-buttons-container">
                <button :class="{ current: filterBy === 'likes' }" @click="filterBy = 'likes'">Likes</button>
                <button :class="{ current: filterBy === 'date' || filterBy === '' }" @click="filterBy = 'date'">Date</button>
            </div>
            <div class="message-item" v-for="(message, index) in filteredMessages" :key="index">
                <message-item :message="message"/>
            </div>
        </div>
</template>

