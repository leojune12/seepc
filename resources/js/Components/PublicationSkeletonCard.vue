<template>
    <div class="md:border-l md:border-r border-t border-b bg-white shadow rounded-none md:rounded-xl px-3 md:px-4 animate-pulse">
        <div class="flex flex-row py-3 items-center">
            <div class="mr-2">
                <div
                    class="w-10 h-10 rounded-full object-cover border border-gray-300 bg-gray-300"
                >
                </div>
            </div>
            <div>
                <div
                    class="h-4 bg-gray-300 w-60 mb-2"
                >
                </div>
                <div
                    class="h-3 bg-gray-300 w-20"
                >
                </div>
            </div>
        </div>
        <div>
            <div class="w-72 h-4 bg-gray-300 mb-3"></div>
            <div
                class="w-full h-64 rounded-xl border bg-gray-300"
            >
            </div>
        </div>
        <div class="flex py-2">
            <div
                class="h-9 rounded bg-gray-300 flex-1"
            >
            </div>
            <div class="w-2 flex-none"></div>
            <div
                class="h-9 rounded bg-gray-300 flex-1"
            >
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PublicationSkeletonCard"
    }
</script>

<style scoped>

</style>
