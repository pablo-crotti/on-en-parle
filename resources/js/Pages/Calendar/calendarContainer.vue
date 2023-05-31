<script>
import DayItem from '@/Pages/Calendar/dayItem.vue';
import axios from 'axios';

export default {
  components: {
    DayItem
  },
  data() {
    return {
        months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        transmissions: []
    };
  },
  emits: ['updateMonth', 'update-month'],
  props: ['month', 'year', 'clDays'],
  methods: {
    isCurrentDay(day) {
      const today = new Date();
      if (this.month !== today.getMonth() + 1) {
        return false;
      }
      return day === today.getDate();
    },
    previousMonth() {
      const newMonth = this.month === 1 ? 12 : this.month - 1;
      const newYear = this.month === 1 ? this.year - 1 : this.year;
      this.$emit('update-month', newMonth, newYear);
    },
    nextMonth() {
      const newMonth = this.month === 12 ? 1 : this.month + 1;
      const newYear = this.month === 12 ? this.year + 1 : this.year;
      this.$emit('update-month', newMonth, newYear);
    },
    getRooms() {
            axios.get('/transmissions/dates')
                .then(response => {
                    this.transmissions = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
    hasTransmission(day) {
        if (day) {
          const date = this.year + '-' + this.month + '-' + day
          const formattedDate = new Date(date).toISOString().split('T')[0];
          const foundTransmission = this.transmissions.find(transmission => transmission.startsWith(formattedDate));
        
          return foundTransmission ? true : false;
        } else {
          return false;
        }
    }
    },
    
    created() {
        this.getRooms()
    },
};
</script>

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
    <!-- <p style="color:white;">{{ transmissions }}</p> -->
    <!-- <p style="color:white;" v-for="(t, i) in transmissions" :key="i">{{ t }}</p> -->
    <div class="day-item" v-for="(day, index) in clDays" :key="index">
      <DayItem :day="day" :month="month" :year="year" :current="isCurrentDay(day)" :hasTransmission="hasTransmission(day)"/>
    </div>
  </div>
  
</template>
