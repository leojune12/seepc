<template>
    <div
        class="flex mt-1 animate-pulse"
    >
        <div class="flex-none mr-2">
            <div
                class="rounded-full border-gray-300 bg-gray-300"
                :class="[ reply ? 'w-7 h-7' : 'w-9 h-9' ]"
            >
            </div>
        </div>
        <div class="flex-1">
            <div
                class="flex items-center"
            >
                <div
                    class="bg-gray-100 rounded-2xl px-3 py-2 h-16"
                >
                    <div class="h-3 bg-gray-300 w-24 mb-2">
                    </div>
                    <div class="h-3 bg-gray-300 w-40 mb-1">
                    </div>
                    <div class="h-3 bg-gray-300 w-32">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CommentSkeleton",
        props: {
            reply: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped>

</style>
