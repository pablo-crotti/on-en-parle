<script>
import DayItem from '@/Pages/Calendar/dayItem.vue';
import TransmissionCard from '@/Pages/MyComponents/transmission-card.vue';
import axios from 'axios';

export default {
  components: {
    DayItem,
    TransmissionCard
  },
  data() {
    return {
        months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        transmissions: [],
        selectedTransmission: null,
        selectedRoom: null,
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
    getRoom(id) {
      // alert(id)
      axios.get('/chat/room/' + id)
          .then(response => {
              this.selectedRoom = response.data
          })
          .catch(error => {
              console.log(error)
          })
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
      // alert(this.transmissions)
        if (day) {
          const date = this.year + '-' + this.month + '-' + day
          const formattedDate = new Date(date).toISOString().split('T')[0];
          const foundTransmissionId = Object.entries(this.transmissions).find(([id, transmission]) => transmission.startsWith(formattedDate))?.[0];

          
          // alert(foundTransmission)

          return foundTransmissionId || false;
        } else {
          return false;
        }
    },
    handleTransmissionClick(transmissionId) {
      this.getRoom(transmissionId)
      this.selectedTransmission = transmissionId;
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
      <DayItem :day="day" :month="month" :year="year" :current="isCurrentDay(day)" :hasTransmission="hasTransmission(day)" @transmission-click="handleTransmissionClick"/>
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
