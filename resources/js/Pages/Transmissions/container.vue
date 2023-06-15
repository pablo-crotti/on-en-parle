<template>
    <AppLayout title="On en parle | Émissions">
        <div class="transmissions-wrapper">
            <div
                class="transmission-card-container"
                v-for="(chatRoom, index) in chatRooms"
                :key="index"
            >
                <a
                    :href="'questions/' + chatRoom.id"
                    v-if="chatRoom.on_air"
                    class="on-air"
                >
                    <transmission-card :room="chatRoom" />
                </a>

                <a :href="'questions/' + chatRoom.id" v-else>
                    <transmission-card :room="chatRoom" />
                </a>
            </div>
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: Provides the layout structure for user pages.
 *
 * Component: TransmissionCard
 * Description: A custom component for displaying transmission details.
 */
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import TransmissionCard from "@/Pages/MyComponents/transmission-card.vue";

export default {
    components: {
        AppLayout,
        TransmissionCard,
    },
    data: function () {
        return {
            chatRooms: [], // Holds the list of chat rooms
        };
    },
    methods: {
        /**
         * Fetches the list of chat rooms from the server.
         */
        getRooms() {
            // ...
        },
    },
    created() {
        /**
         * Executes when the component is created.
         * Fetches the initial list of chat rooms and sets up Echo event listeners.
         */
        this.getRooms();

        // Set up Echo event listener for live status updates
        const liveChannel = Echo.channel("live.status");
        liveChannel.listen(".live.status.new", (e) => {
            this.getRooms();
        });

        // Set up Echo event listener for chat room updates
        const chatChannel = Echo.channel("rooms.update");
        chatChannel.listen(".event.on.rooms", (e) => {
            this.getRooms();
        });
    },
};
</script>
