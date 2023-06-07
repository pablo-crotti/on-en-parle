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
            },
            askConfirm(id) {
                if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
                    this.deleteUser(id);
                }
            },
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
                <p> {{ user.name }}</p>
                <p>{{ user.email }}</p>
                <button @click="askConfirm(user.id)">
                    <span class="material-symbols-outlined">
                        delete
                    </span>
                </button>
            </div>
        </div>
    </AppLayout>
</template>