<template>
    <nav class="bg-white border-b border-gray-200 sticky top-0 left-72 z-50 w-full">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between lg:justify-end items-center h-16">
                <div class="lg:hidden">
                    <app-logo-name />
                </div>
                <div v-if="$page.props.user" class="hidden lg:flex lg:items-center">
                    <!-- Settings Dropdown -->
                    <div class="relative">
                        <jet-dropdown align="right" width="48">
                            <template #trigger>
                                <div class="flex">
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-300" :src="[ $page.props.user.profile_photo_path ? '/storage/'+$page.props.user.profile_photo_path : $page.props.user.profile_photo_url ]" :alt="$page.props.user.name" />
                                    </button>

                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.user.name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </template>

                            <template #content>
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Account
                                </div>

                                <jet-dropdown-link :href="route('profile.show')">
                                    Account
                                </jet-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <jet-dropdown-link as="button">
                                        Logout
                                    </jet-dropdown-link>
                                </form>
                            </template>
                        </jet-dropdown>
                    </div>
                </div>

                <div v-else class="hidden lg:flex lg:items-center">
                    <inertia-link :href="route('login')" class="whitespace-nowrap text-base font-medium text-gray-500 hover:text-gray-900 px-4 py-2 hover:bg-gray-100 rounded-md h-10">
                        Sign in
                    </inertia-link>
                    <inertia-link :href="route('register')" class="h-10 ml-4 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Sign up
                    </inertia-link>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center lg:hidden">
                    <span @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="lg:hidden">
            <div v-if="$page.props.user" class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3" >
                        <img class="h-10 w-10 rounded-full object-cover border border-gray-300" :src="[ $page.props.user.profile_photo_path ? '/storage/'+$page.props.user.profile_photo_path : $page.props.user.profile_photo_url ]" :alt="$page.props.user.name" />
                    </div>

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <jet-responsive-nav-link :href="route('publications')" :active="route().current('publications')">
                        Publications
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link
                        :href="route('my-profile')"
                        :active="route().current('my-profile')"
                    >
                        Profile
                    </jet-responsive-nav-link>
                    <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                        Account
                    </jet-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" @submit.prevent="logout">
                        <jet-responsive-nav-link as="button">
                            Logout
                        </jet-responsive-nav-link>
                    </form>
                </div>
            </div>

            <div v-else class="p-4">
                <inertia-link :href="route('register')" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Sign up
                </inertia-link>
                <p class="mt-6 text-center text-base font-medium text-gray-500">
                    Existing user?
                    <inertia-link :href="route('login')" class="text-indigo-600 hover:text-indigo-500">
                        Sign in
                    </inertia-link>
                </p>
            </div>
        </div>
    </nav>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import AppLogoName from "@/Components/AppLogoName";

    export default {
        name: "Navbar",

        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            AppLogoName
        },

        data() {
            return {
                showingNavigationDropdown: false,
            }
        },

        methods: {
            logout() {
                this.$inertia.post(route('logout'));
            },
        }
    }
</script>

<style scoped>

</style>
