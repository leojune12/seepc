<template>
    <app-layout>
        Publication
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        name: "Publication",
        components: {
            AppLayout,
        },
        props: [
            'publications'
        ]
    }
</script>

<style scoped>

</style>
