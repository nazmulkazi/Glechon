<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="tw-min-h-screen tw-bg-gray-100">
            <nav class="tw-bg-white tw-border-b tw-border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="tw-max-w-7xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    <div class="tw-flex tw-justify-between tw-h-16">
                        <div class="tw-flex">
                            <!-- Logo -->
                            <div class="shrink-0 tw-flex tw-items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="tw-block tw-h-9 tw-w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="tw-hidden tw-space-x-8 sm:tw--my-px sm:tw-ml-10 sm:tw-flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Datasets
                                </NavLink>
                            </div>
                        </div>

                        <div class="tw-hidden sm:tw-flex sm:tw-items-center sm:tw-ml-6">
                            <!-- Dataset Dropdown -->
                            <!-- 
                            <div class="tw-ml-3 tw-relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="tw-inline-flex tw-rounded-md">
                                            <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                                Datasets

                                                <svg 
                                                    class="tw-ml-2 -tw-mr-0.5 tw-h-4 tw-w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('dataset.import')">
                                            Import Dataset
                                        </DropdownLink>

                                        <DropdownLink :href="route('dataset.create')">
                                            Create Dataset
                                        </DropdownLink>

                                        <div class="tw-border-t tw-border-gray-100" />
                                        
                                        <DropdownLink :href="route('dataset.import')">
                                            Manage Dataset
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            -->
                            
                            
                            <!-- Users Dropdown -->
                            <div v-if="can('any-user')" class="tw-ml-3 tw-relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="tw-inline-flex tw-rounded-md">
                                            <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                                Users

                                                <svg 
                                                    class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!--  -->
                                        <DropdownLink v-if="can('add-user')" :href="route('users.create')">
                                            Add Users
                                        </DropdownLink>
                                        
                                        <DropdownLink v-if="can('manage-user')" :href="route('users.index')">
                                            Manage Users
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            
                            <div class="tw-ml-3 tw-relative">
                                <!-- Teams Dropdown -->
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="tw-inline-flex tw-rounded-md">
                                            <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-white hover:tw-bg-gray-50 hover:tw-text-gray-700 focus:tw-outline-none focus:tw-bg-gray-50 active:tw-bg-gray-50 tw-transition">
                                                {{ $page.props.user.current_team.name }}

                                                <svg
                                                    class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="tw-w-60">
                                            <!-- Team Management -->
                                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                                <div class="tw-block tw-px-4 tw-py-2 tw-text-xs tw-text-gray-400">
                                                    Manage Team
                                                </div>

                                                <!-- Team Settings -->
                                                <DropdownLink :href="route('teams.show', $page.props.user.current_team)">
                                                    Team Settings
                                                </DropdownLink>

                                                <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                    Create New Team
                                                </DropdownLink>

                                                <div class="tw-border-t tw-border-gray-100" />

                                                <!-- Team Switcher -->
                                                <div class="tw-block tw-px-4 tw-py-2 tw-text-xs tw-text-gray-400">
                                                    Switch Teams
                                                </div>

                                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="tw-flex tw-items-center">
                                                                <svg
                                                                    v-if="team.id == $page.props.user.current_team_id"
                                                                    class="tw-mr-2 tw-h-5 tw-w-5 tw-text-green-400"
                                                                    fill="none"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="tw-ml-3 tw-relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="tw-flex tw-text-sm tw-border-2 tw-border-transparent tw-rounded-full focus:tw-outline-none focus:tw-border-gray-300 tw-transition">
                                            <img class="tw-h-8 tw-w-8 tw-rounded-full tw-object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                        </button>

                                        <span v-else class="tw-inline-flex tw-rounded-md">
                                            <button type="button" class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-border tw-border-transparent tw-text-sm tw-leading-4 tw-font-medium tw-rounded-md tw-text-gray-500 tw-bg-white hover:tw-text-gray-700 focus:tw-outline-none tw-transition">
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="tw-ml-2 tw--mr-0.5 tw-h-4 tw-w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="tw-block tw-px-4 tw-py-2 tw-text-xs tw-text-gray-400">
                                            Manage Account
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Profile
                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink>

                                        <div class="tw-border-t tw-border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="tw--mr-2 tw-flex tw-items-center sm:tw-hidden">
                            <button class="tw-inline-flex tw-items-center tw-justify-center tw-p-2 tw-rounded-md tw-text-gray-400 hover:tw-text-gray-500 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 focus:tw-text-gray-500 tw-transition" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="tw-h-6 tw-w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'tw-hidden': showingNavigationDropdown, 'tw-inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'tw-hidden': ! showingNavigationDropdown, 'tw-inline-flex': showingNavigationDropdown }"
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

                <!-- Responsive Navigation Menu -->
                <div :class="{'tw-block': showingNavigationDropdown, 'tw-hidden': ! showingNavigationDropdown}" class="sm:tw-hidden">
                    <div class="tw-pt-2 tw-pb-3 tw-space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Datasets
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
                        <div class="tw-flex tw-items-center tw-px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 tw-mr-3">
                                <img class="tw-h-10 tw-w-10 tw-rounded-full tw-object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                            </div>

                            <div>
                                <div class="tw-font-medium tw-text-base tw-text-gray-800">
                                    {{ $page.props.user.name }}
                                </div>
                                <div class="tw-font-medium tw-text-sm tw-text-gray-500">
                                    {{ $page.props.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="tw-mt-3 tw-space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="tw-border-t tw-border-gray-200" />

                                <div class="tw-block tw-px-4 tw-py-2 tw-text-xs tw-text-gray-400">
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', $page.props.user.current_team)" :active="route().current('teams.show')">
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </ResponsiveNavLink>

                                <div class="tw-border-t tw-border-gray-200" />

                                <!-- Team Switcher -->
                                <div class="tw-block tw-px-4 tw-py-2 tw-text-xs tw-text-gray-400">
                                    Switch Teams
                                </div>

                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ResponsiveNavLink as="button">
                                            <div class="tw-flex tw-items-center">
                                                <svg
                                                    v-if="team.id == $page.props.user.current_team_id"
                                                    class="tw-mr-2 tw-h-5 tw-w-5 tw-text-green-400"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </ResponsiveNavLink>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="tw-bg-white tw-shadow">
                <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8">
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
