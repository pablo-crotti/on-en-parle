<template>
    <AppLayout title="On en parle | Émissions">
        <div class="calendar-wrapper">
            <CalendarContainer
                :year="year"
                :clDays="calendarDays"
                :month="month"
                @update-month="updateMonth"
            />
        </div>
    </AppLayout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: The layout component for the application.
 * Component: CalendarContainer
 * Description: The container component for the calendar.
 */
import AppLayout from "@/Layouts/AppLayoutUser.vue";
import CalendarContainer from "./calendarContainer.vue";

export default {
    components: {
        AppLayout,
        CalendarContainer,
    },
    watch: {
        month: {
            immediate: true,
            handler(newMonth, oldMonth) {
                this.totalDays = this.getDaysInMonth(newMonth, this.year);
                this.startOffset = this.getDayOfWeek(newMonth, this.year);
            },
        },
        year: {
            immediate: true,
            handler(newYear, oldYear) {
                this.updateTotalDays();
            },
        },
    },
    data() {
        return {
            month: new Date().getMonth() + 1,
            year: new Date().getFullYear(),
            startOffset: "",
            totalDays: 0,
            displayTransmissionId: "",
        };
    },
    methods: {
        /**
         * Gets the number of days in the specified month and year.
         * @param {number} month - The month.
         * @param {number} year - The year.
         * @returns {number} - The number of days in the month.
         */
        getDaysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        },
        /**
         * Gets the day of the week for the first day of the specified month and year.
         * @param {number} month - The month.
         * @param {number} year - The year.
         * @returns {number} - The day of the week (0-6, where 0 is Sunday).
         */
        getDayOfWeek(month, year) {
            const firstDayOfMonth = new Date(year, month - 1, 1);
            let dayOfWeek = firstDayOfMonth.getDay();

            if (dayOfWeek === 0) {
                dayOfWeek = 7;
            }
            return dayOfWeek - 1;
        },
        /**
         * Updates the current month and year.
         * @param {number} newMonth - The new month.
         * @param {number} newYear - The new year.
         */
        updateMonth(newMonth, newYear) {
            this.month = newMonth;
            this.year = newYear;
        },
        /**
         * Updates the total number of days in the current month.
         */
        updateTotalDays() {
            this.totalDays = this.getDaysInMonth(this.month, this.year);
        },
    },
    computed: {
        /**
         * Generates an array of calendar days for display.
         * @returns {Array} - The array of calendar days.
         */
        calendarDays() {
            const emptyBefore = new Array(this.startOffset).fill(null);
            const days = Array.from(
                { length: this.totalDays },
                (_, index) => index + 1
            );
            const emptyAfter = new Array(
                (7 - ((this.totalDays + this.startOffset) % 7)) % 7
            ).fill(null);

            return [...emptyBefore, ...days, ...emptyAfter];
        },
    },
    created() {
        this.totalDays = this.getDaysInMonth(this.month, this.year);
        this.startOffset = this.getDayOfWeek(this.month, this.year);
    },
};
</script>
