<template>
    <div class="message-container">
        <div
            style="
                display: flex;
                flex-direction: row;
                width: 80%;
                justify-content: space-between;
            "
        >
            <h1>Vos questions</h1>
            <div class="filter-buttons-container">
                <button
                    :class="{ current: filterBy === 'likes' }"
                    @click="filterBy = 'likes'"
                >
                    Likes
                </button>
                <button
                    :class="{ current: filterBy === 'date' || filterBy === '' }"
                    @click="filterBy = 'date'"
                >
                    Date
                </button>
            </div>
        </div>

        <div
            class="message-item"
            v-for="(message, index) in filteredMessages"
            :key="index"
        >
            <message-item :message="message" />
        </div>
    </div>
</template>

<script>
/**
 * Component: messageItem
 * Description: Component of a message.
 */
import MessageItem from "./messageItem.vue";

export default {
    components: {
        MessageItem,
    },
    props: ["messages"],
    data() {
        return {
            filteredMessages: [],
            filterBy: "",
        };
    },
    methods: {
        /**
         * Filter the messages based on the selected filter option.
         */
        filterMessages() {
            if (this.filterBy === "date") {
                this.filteredMessages = [...this.messages];
            } else if (this.filterBy === "likes") {
                this.filteredMessages = [...this.messages].sort(
                    (a, b) => b.nb_likes - a.nb_likes
                );
            } else {
                this.filteredMessages = [...this.messages];
            }
        },
    },
    watch: {
        /**
         * Watch for changes in the selected filter option.
         * Call the filterMessages method when the filterBy property changes.
         */
        filterBy() {
            this.filterMessages();
        },

        /**
         * Watch for changes in the messages property.
         * Call the filterMessages method when the messages property changes.
         */
        messages() {
            this.filterMessages();
        },
    },

    /**
     * Component lifecycle hook.
     * Call the filterMessages method when the component is created.
     */
    created() {
        this.filterMessages();
    },
};
</script>
