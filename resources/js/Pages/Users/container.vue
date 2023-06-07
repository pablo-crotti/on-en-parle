<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout
        },
        data() {
            return {
                users: []
            }
        },
        methods: {
            getUsers() {
                axios.get('/admin/users')
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        created() {
            this.getUsers();
        }
    }
</script>

<template>
    <AppLayout title="On en parle | Administration (Animateur)">
        <div class="users-container">
            <div class="user" v-for="user in users">
                <p style="color: white;"> {{ user.name }}</p>
                <p style="color: white;">{{ user.email }}</p>
                <button>
                    Supprimer
                </button>
            </div>
        </div>
    </AppLayout>
</template>