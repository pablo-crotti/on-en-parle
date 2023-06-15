<template>
    <div :class="['admin-message', { 'from-me': message.user.id === user }]">
        <div class="admin-message-user">
            <p>{{ message.user.name }}</p>
        </div>
        <div class="admin-message-content">
            <p>{{ message.content }}</p>
        </div>
        <div class="admin-message-time">
            <p>{{ formatRelativeTime(message.created_at) }}</p>
        </div>
    </div>
</template>

<script>
import { formatRelativeTime } from "../../utils/timeFormat";
export default {
    props: ["message", "user"],
    methods: {
        formatRelativeTime,
    },
};
</script>
