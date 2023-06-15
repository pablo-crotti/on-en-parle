<template>
    <div class="head">
        <div class="month">
            <p class="prev" @click="previousMonth">&#10094;</p>
            <p>{{ months[month - 1] }} {{ year }}</p>
            <p class="next" @click="nextMonth">&#10095;</p>
        </div>
    </div>
    <div class="weekdays">
        <div class="week-day"><p>L</p></div>
        <div class="week-day"><p>M</p></div>
        <div class="week-day"><p>M</p></div>
        <div class="week-day"><p>J</p></div>
        <div class="week-day"><p>V</p></div>
        <div class="week-day"><p>S</p></div>
        <div class="week-day"><p>D</p></div>
    </div>
    <div class="days-container">
        <div class="day-item" v-for="(day, index) in clDays" :key="index">
            <DayItem
                :day="day"
                :month="month"
                :year="year"
                :current="isCurrentDay(day)"
                :hasTransmission="hasTransmission(day)"
                @transmission-click="handleTransmissionClick"
            />
        </div>
    </div>
    <div class="calendar-transmission-wrapper">
        <div class="transmission-card-container" v-if="selectedRoom">
            <a :href="'questions/' + selectedRoom.id">
                <TransmissionCard :room="selectedRoom" />
            </a>
        </div>
    </div>
</template>

<script>
/**
 * Component: DayItem
 * Description: Component for displaying a day in the calendar.
 * 
 * Component: TransmissionCard
 * Description: Component for displaying a transmission card.
 */
import DayItem from "@/Pages/Calendar/dayItem.vue";
import TransmissionCard from "@/Pages/MyComponents/transmission-card.vue";
import axios from "axios";

export default {
    components: {
        DayItem,
        TransmissionCard,
    },
    data() {
        return {
            /**
             * The list of month names.
             */
            months: [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Août",
                "Septembre",
                "Octobre",
                "Novembre",
                "Décembre",
            ],
            /**
             * The list of transmissions.
             */
            transmissions: [],
            /**
             * The ID of the selected transmission.
             */
            selectedTransmission: null,
            /**
             * The selected room object.
             */
            selectedRoom: null,
        };
    },
    emits: ["updateMonth", "update-month"],
    props: ["month", "year", "clDays"],
    methods: {
        /**
         * Checks if the given day is the current day.
         * @param {number} day - The day to check.
         * @returns {boolean} - True if the day is the current day, false otherwise.
         */
        isCurrentDay(day) {
            const today = new Date();
            if (this.month !== today.getMonth() + 1) {
                return false;
            }
            return day === today.getDate();
        },
        /**
         * Handles the click event for the previous month button.
         * Emits an event to update the calendar to the previous month.
         */
        previousMonth() {
            const newMonth = this.month === 1 ? 12 : this.month - 1;
            const newYear = this.month === 1 ? this.year - 1 : this.year;
            this.$emit("update-month", newMonth, newYear);
        },
        /**
         * Handles the click event for the next month button.
         * Emits an event to update the calendar to the next month.
         */
        nextMonth() {
            const newMonth = this.month === 12 ? 1 : this.month + 1;
            const newYear = this.month === 12 ? this.year + 1 : this.year;
            this.$emit("update-month", newMonth, newYear);
        },
        /**
         * Retrieves the room object for the given room ID.
         * @param {number} id - The ID of the room.
         */
        getRoom(id) {
            axios
                .get("/chat/room/" + id)
                .then((response) => {
                    this.selectedRoom = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        /**
         * Retrieves the list of transmissions.
         */
        getRooms() {
            axios
                .get("/transmissions/dates")
                .then((response) => {
                    this.transmissions = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        /**
         * Checks if a transmission exists for the given day.
         * @param {number} day - The day to check.
         * @returns {boolean} - True if a transmission exists, false otherwise.
         */
        hasTransmission(day) {
            if (day) {
                const date = this.year + "-" + this.month + "-" + day;
                const formattedDate = new Date(date)
                    .toISOString()
                    .split("T")[0];
                const foundTransmissionId = Object.entries(
                    this.transmissions
                ).find(([id, transmission]) =>
                    transmission.startsWith(formattedDate)
                )?.[0];

                return foundTransmissionId || false;
            } else {
                return false;
            }
        },
        /**
         * Handles the click event for a transmission.
         * Retrieves the room object and sets the selected transmission.
         * @param {number} transmissionId - The ID of the selected transmission.
         */
        handleTransmissionClick(transmissionId) {
            this.getRoom(transmissionId);
            this.selectedTransmission = transmissionId;
        },
    },

    created() {
        this.getRooms();
    },
};
</script>
