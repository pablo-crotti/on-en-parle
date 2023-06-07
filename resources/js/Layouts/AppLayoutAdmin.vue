<script>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

export default {
    props: {
        title: String,
    },
    components: {
        Head,
        Link,
        ApplicationMark,
        Banner,
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink,
    },
    data() {
        return {
            showingNavigationDropdown: false,
            menuList: [],
            currentURL: window.location.href,
            upcomingPrograms: [],
        }
    },
    methods: {
        setMenu(){
            if (this.currentURL.includes('admin/reception')) {
                this.menuList = [["inbox", "Inbox"], ["archives", "Archives"]];
            } else if (this.currentURL.includes('admin/administration')) {
                this.menuList = [["manage", "Gestion"], ["control", "Régie"], ["animator", "Animateur"]];
            } else if (this.currentURL.includes('admin/programs')) {
                this.menuList = [["newProgramm", "Nouvelle émission"], ["live", "Live"], ["listPrograms", "Émissions"]];
            }
        },
        getUpcomingPrograms(){
            axios.get('/prochaines-emissions')
                .then(response => {
                    this.upcomingPrograms = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        setCurrentProgram(newId){
            let regex = /\d+$/;
            let newURL = this.currentURL.replace(regex, newId);
            window.location.replace(newURL);
        },
        isNavLinkActive(url) {
            return this.currentURL.includes(url);
        },
        getRoute(routeName){
            if(routeName === "archives"){
                return route(routeName, { id: 1 });
            } else {
                return route(routeName);
            }
        }
    },
    created() {
        this.setMenu();
        this.getUpcomingPrograms();
    },
}


const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="header-logo">
                <img src="https://www.rts.ch/2021/01/05/11/09/10321771.image?&w=6000&h=600" alt="">
        </div>

        <div class="bg-gray-100">
            <nav class="bg-black border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="hidden adMenu space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('inbox')" :active="isNavLinkActive('admin/reception')">
                                    Réception
                                </NavLink>
                                <NavLink :href="route('manage')" :active="isNavLinkActive('admin/administration')">
                                    Administration
                                </NavLink>
                                <NavLink :href="route('newProgramm')" :active="isNavLinkActive('admin/programs')">
                                    Émissions
                                </NavLink>
                            </div> 

                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink v-for="item in menuList" :key="item" :href="getRoute(item[0])" :active="route().current(item[0])">
                                    {{ item[1] }}
                                </NavLink>
                            </div>
                            <div class="program-selection">
                                <div class="program-item" v-for="(program, index) in upcomingPrograms" :key="index" @click="setCurrentProgram(program.id)">{{ program.title }}</div>
                            </div>
                            
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Gestion du compte
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Profile
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Déconnexion
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <div class="mobile-menu-container">
                                <div class="mobile-calendar">
                                    
                                </div>
                                
                                <button class="hamb-button inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="user-name">
                                    {{ $page.props.auth.user.name }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Déconnexion
                                </ResponsiveNavLink>
                            </form>

                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
    .program-selection {
        display: none;
        /* display: flex;
        background-color: white;
        height: 500px;
        z-index: 2; */
    }
    /* .program-item {

    } */
</style>