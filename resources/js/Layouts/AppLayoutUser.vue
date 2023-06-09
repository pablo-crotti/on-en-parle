<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />
        <!-- <div class="banner_header b-lazy b-processed b-loaded" data-ratios="2.85" data-ratiom="5.41" data-ratiol="8" data-ratio="10" style="background-image: url(&quot;https://www.rts.ch/2021/01/05/11/09/10321771.image?&amp;w=6000&amp;h=600&quot;);"></div> -->
        <div class="header-logo">
            <img
                src="https://www.rts.ch/2021/01/05/11/09/10321771.image?&w=6000&h=600"
                alt=""
            />
        </div>

        <div class="bg-gray-100">
            <nav class="bg-black border-b">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-left sm:hidden">
                            <div class="mobile-menu-container">
                                <div class="mobile-calendar">
                                    <NavLink
                                        :href="route('calendar')"
                                        :active="route().current('calendar')"
                                    >
                                        <span class="material-symbols-outlined">
                                            calendar_month
                                        </span>
                                    </NavLink>
                                </div>

                                <button
                                    class="hamb-button inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                    @click="
                                        showingNavigationDropdown =
                                            !showingNavigationDropdown
                                    "
                                >
                                    <svg
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{
                                                hidden: showingNavigationDropdown,
                                                'inline-flex':
                                                    !showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{
                                                hidden: !showingNavigationDropdown,
                                                'inline-flex':
                                                    showingNavigationDropdown,
                                            }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex">
                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('calendar')"
                                    :active="route().current('calendar')"
                                >
                                    <span class="material-symbols-outlined">
                                        calendar_month
                                    </span>
                                </NavLink>
                                <NavLink
                                    :href="route('index')"
                                    :active="route().current('index')"
                                >
                                    À la une
                                </NavLink>

                                <NavLink
                                    :href="route('transmissions')"
                                    :active="route().current('transmissions')"
                                >
                                    Émissions
                                </NavLink>
                                <NavLink
                                    :href="route('contact')"
                                    :active="route().current('contact')"
                                >
                                    Contact
                                </NavLink>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('index')"
                            :active="route().current('index')"
                        >
                            À la une
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('transmissions')"
                            :active="route().current('transmissions')"
                        >
                            Émissions
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('contact')"
                            :active="route().current('contact')"
                        >
                            Contact
                        </ResponsiveNavLink>
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
