<script>
import AppLayout from '@/Layouts/AppLayoutUser.vue';
import CalendarContainer from './calendarContainer.vue';
import axios from 'axios';
import TransmissionCard from '@/Pages/MyComponents/transmission-card.vue';

export default {
  components: {
    AppLayout,
    CalendarContainer
    // TransmissionCard
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
      startOffset: '',
      totalDays: 0,
      displayTransmissionId: ''
    };
  },
  methods: {
    getDaysInMonth(month, year) {
      return new Date(year, month, 0).getDate();
    },
    getDayOfWeek(month, year) {
      const firstDayOfMonth = new Date(year, month - 1, 1);
      let dayOfWeek = firstDayOfMonth.getDay();
      
      if (dayOfWeek === 0) {
        
        dayOfWeek = 7;
      }
      return dayOfWeek - 1; 
    },
    updateMonth(newMonth, newYear) {
      this.month = newMonth;
      this.year = newYear;
    },
    updateTotalDays() {
      this.totalDays = this.getDaysInMonth(this.month, this.year);
    },
  },
  computed: {
    calendarDays() {
      const emptyBefore = new Array(this.startOffset).fill(null); 
      const days = Array.from({ length: this.totalDays }, (_, index) => index + 1); 
      const emptyAfter = new Array((7 - (this.totalDays + this.startOffset) % 7) % 7).fill(null);

      return [...emptyBefore, ...days, ...emptyAfter];
    },
  },
  created() {
    this.totalDays = this.getDaysInMonth(this.month, this.year);
    this.startOffset = this.getDayOfWeek(this.month, this.year);
  },
};
</script>

<template>
  <AppLayout title="On en parle | Émissions">
    <div class="calendar-wrapper">
      <CalendarContainer :year="year" :clDays="calendarDays" :month="month" @update-month="updateMonth" />
    </div>
      
  </AppLayout>
</template>
