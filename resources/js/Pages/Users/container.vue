<script>
    import AppLayout from '@/Layouts/AppLayoutAdmin.vue';
    import axios from 'axios';

    export default {
        components: {
            AppLayout
        },
        data() {
            return {
                users: [],
                userToDelete: null
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
                const container = document.querySelector('.user-delete-confirmation-wrapper');
                container.setAttribute('data-display-confirm', 'true');
                container.querySelector('span').innerHTML = this.users.find(user => user.id === id).name;
                this.userToDelete = id;
            },
            cancelDelete() {
                const container = document.querySelector('.user-delete-confirmation-wrapper');
                container.setAttribute('data-display-confirm', 'false');
                this.userToDelete = null;
            },
            deleteUser() {
                const container = document.querySelector('.user-delete-confirmation-wrapper');
                const id = container.querySelector('span').innerHTML;
                axios.post('/admin/users/delete/' + this.userToDelete)
                    .then(response => {
                        if (response.status === 200) {
                            this.getUsers();
                            container.setAttribute('data-display-confirm', 'false');
                        }
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
        <div class="user-delete-confirmation-wrapper" data-display-confirm="false">
            <div class="user-delete-confirmation-container">
                <p>Êtes-vous sûr de vouloir supprimer cet utilisateur : <span></span> ?</p>
                <div class="user-delete-confirmation-buttons">
                    <button class="user-delete-cancel-button" @click="cancelDelete">
                        Annuler
                    </button>
                    <button class="user-delete-confirmation-button" @click="deleteUser">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
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