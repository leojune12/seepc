<template>
    <inertia-link :href="route('publications')" class="flex" preserve-scroll>
        <jet-application-mark class="block h-9 w-auto" />
        <span class="font-bold text-2xl text-gray-900 ml-2">seePC</span>
    </inertia-link>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'

    export default {
        name: "AppLogoName",
        components: {
            JetApplicationMark
        }
    }
</script>

<style scoped>

</style>
