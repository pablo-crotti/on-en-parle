<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import AdminProgram from '@/Pages/MyComponents/admin-program.vue';
    import axios from 'axios';
    import dropdownFilter from '@/Pages/MyComponents/dropdownFilter.vue';

    export default {
        components: {
            AppLayout,
            AdminProgram,
            dropdownFilter
        },
        data() {
            return {
                programs: [],
                filteredPrograms: [], 
                filterBy: '', 
                isSelectOpen: false,
                categories: [
                {
                    name: 'Date de diffusion (décroissante)',
                    value: 'date'
                },
                {
                    name: 'Nombre de messages',
                    value: 'interactions'
                }
    ]
            }
        },
        methods: {
            handleFilterApplied(filterData) {
            this.filterBy = filterData;
    },
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
            <dropdownFilter :categories="categories" @filter-applied="handleFilterApplied"></dropdownFilter>
            <!--
     
            <div id="dropdown-filter">
              <label for="filter">Trier par 
                   
              </label>
                <select v-model="filterBy" id="filter" @change="handleFilterChange">
                    <option value="date" >Date</option>
                    <option value="interactions">Interactions</option>
                </select>  
            </div>
             -->
            
        </div>
        
        <div class="programs-wrapper">

            

            <div class="row-program" v-for="program in filteredPrograms" :key="program.id">
                
                <AdminProgram class="item-program" :program="program"/>


            </div>
        </div>
    </AppLayout>
</template>

<style>

</style>