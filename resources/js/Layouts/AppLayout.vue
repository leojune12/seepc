<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100 relative flex">
            <!-- Sidebar -->
            <sidebar />

            <div class="w-full">
                <!-- Navbar -->
                <navbar />

                <!-- Page Content -->
                <main class="z-10 flex-1">
                    <slot></slot>
                </main>
            </div>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple>
            </portal-target>
        </div>
    </div>
</template>

<script>
    import JetBanner from '@/Jetstream/Banner'
    import Sidebar from "@/Layouts/Sidebar"
    import Navbar from "@/Layouts/Navbar"

    export default {
        components: {
            JetBanner,
            Sidebar,
            Navbar
        },
    }
</script>
