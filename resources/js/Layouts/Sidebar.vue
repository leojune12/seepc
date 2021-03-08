<template>
    <nav
        class="bg-white w-64 h-screen border-r border-gray-200 flex-none sticky top-0 hidden lg:block"
    >
        <div class="">
            <!-- Logo -->
            <div class="flex items-center py-4 h-16 px-8">
                <app-logo-name />
            </div>

            <!-- Navigation Links -->
            <div class="px-4 py-4 font-semibold text-gray-700">
                <inertia-link
                    v-if="!!$page.props.user"
                    :href="route('dashboard')"
                    :class="[ route().current('dashboard') ? activeLink : hoverClass, linkClass ]"
                >
                    <span :class="iconClass">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z" />
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </inertia-link>
                <inertia-link
                    :href="route('publications')"
                    :class="[ route().current('publications') ? activeLink : hoverClass, linkClass ]"
                >
                    <span :class="iconClass">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22,17V7H6V17H22M22,5A2,2 0 0,1 24,7V17C24,18.11 23.1,19 22,19H16V21H18V23H10V21H12V19H6C4.89,19 4,18.11 4,17V7A2,2 0 0,1 6,5H22M2,3V15H0V3A2,2 0 0,1 2,1H20V3H2Z" />
                        </svg>
                    </span>
                    <span>Publications</span>
                </inertia-link>
                <inertia-link
                    v-if="!!$page.props.user"
                    :href="route('user.my-profile')"
                    :class="[ route().current('user.my-profile') ? activeLink : hoverClass, linkClass ]"
                >
                    <span :class="iconClass">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                        </svg>
                    </span>
                    Profile
                </inertia-link>
            </div>
        </div>
    </nav>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetNavLink from '@/Jetstream/NavLink'
    import AppLogoName from "@/Components/AppLogoName";

    export default {
        name: "Sidebar",
        components: {
            JetApplicationMark,
            JetNavLink,
            AppLogoName
        },
        data () {
            return {
                hoverClass: 'hover:bg-gray-100',
                linkClass: 'py-2 px-4 block rounded-md mt-2 flex',
                iconClass: 'mr-3 text-gray-500',
                activeLink: 'bg-gray-200'
            }
        }
    }
</script>

<style scoped>

</style>
