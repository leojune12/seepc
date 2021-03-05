<template>
    <nav
        class="bg-white w-72 h-screen border-r border-gray-200 flex-none sticky top-0 hidden md:block"
    >
        <div class="">
            <!-- Logo -->
            <div class="flex items-center py-4 h-16 px-8">
                <app-logo-name />
            </div>

            <!-- Navigation Links -->
            <div class="px-4 py-4 font-semibold text-gray-700">
                <!--<jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                    Dashboard
                </jet-nav-link>-->
                <!--<jet-nav-link
                    :href="route('publications')"
                    :active="route().current('publications')"
                >
                    Publications
                </jet-nav-link>-->
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
                    <span>All Publications</span>
                </inertia-link>
                <inertia-link
                    v-if="!!$page.props.user"
                    :href="route('dashboard')"
                    :class="[ route().current('dashboard') ? activeLink : hoverClass, linkClass ]"
                >
                    <span :class="iconClass">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M20.5,13A2.5,2.5 0 0,1 23,15.5V16A1,1 0 0,1 24,17V21A1,1 0 0,1 23,22H18A1,1 0 0,1 17,21V17A1,1 0 0,1 18,16V15.5A2.5,2.5 0 0,1 20.5,13M20.5,14A1.5,1.5 0 0,0 19,15.5V16H22V15.5A1.5,1.5 0 0,0 20.5,14M20,4H2V16H15V18H13V20H15V22H7V20H9V18H2C0.89,18 0,17.1 0,16V4C0,2.89 0.89,2 2,2H20A2,2 0 0,1 22,4V11.53C21.41,11.19 20.73,11 20,11V4Z" />
                        </svg>
                    </span>
                    My Publications
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
