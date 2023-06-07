<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import AdminProgram from '@/Pages/MyComponents/admin-program.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout,
            AdminProgram
        },
        data() {
            return {
                programs: [],
                filteredPrograms: [], 
                filterBy: 'date', 
                isSelectOpen: false
            }
        },
        methods: {
            toggleSelect() {
            this.isSelectOpen = !this.isSelectOpen;
    },  
            handleFilterChange(event) {
                this.filterBy = event.target.value;
                
                // Perform any desired actions with the selected value
            },
            filterPrograms() {
    
                if (this.filterBy === 'interactions') {

                    this.filteredPrograms = [...this.programs].sort(
                        (a, b) => b.messages_count - a.messages_count
                    );

                } else if (this.filterBy === 'date') {
                    this.filteredPrograms = [...this.programs]

                } else {
                    this.filteredPrograms = [...this.programs];
                }
            },
        },
        watch: {
            filterBy() {
                this.filterPrograms(); 
            }
        },
        created() {
            axios.get('/chat/rooms-list').then(response => {

                this.programs = response.data;

                this.filteredPrograms = [...this.programs];

            });

    
        }
    }
    
</script>

<template>
    <AppLayout title="On en parle | Émissions (Liste)">
        <div class="programs-header">
            <h1 id="programs-title">Émissions</h1>

            <div id="dropdown-filter">
              <label for="filter" @click="toggleSelect">Trier par 
                    <!-- <span class="material-symbols-outlined" :class="{'rotate': isSelectOpen}">arrow_drop_down</span> -->
              </label>
                <select v-model="filterBy" id="filter" @change="handleFilterChange">
                    <option value="date" >Date</option>
                    <option value="interactions">Interactions</option>
                </select>  
            </div>
            
            
        </div>
        
        <div class="programs-wrapper">

            

            <div class="row-program" v-for="program in filteredPrograms" :key="program.id">
                
                <AdminProgram class="item-program" :program="program"/>


            </div>
        </div>
    </AppLayout>
</template>

<style>
.rotate {
  transform: rotate(180deg);
}
</style>