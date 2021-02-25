<template>
    <div
        class="bg-blue-100 border-l-4 border-blue-400 text-gray-800 px-3 py-4 rounded-md"
    >
        <span>
            {{ message }}
        </span>
    </div>
</template>

<script>
    export default {
        name: "InfoMessage",

        props: [
            'message'
        ]
    }
</script>

<style scoped>

</style>
