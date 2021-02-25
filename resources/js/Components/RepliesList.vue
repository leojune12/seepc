<template>
    <div
        style="min-height: 32px;"
        class="flex text-gray-800 mt-1"
    >
        <div class="flex-none mr-2">
            <img
                :src="getReplyProfilePhoto(reply.user)"
                alt="profile photo"
                class="w-7 h-7 rounded-full object-cover border border-gray-300"
            >
        </div>
        <div class="flex-1">
            <div
                class="flex items-center"
            >
                <div
                    class="bg-gray-100 rounded-2xl px-3 py-2"
                >
                    <div class="font-bold md:font-semibold md:text-sm text-xs">
                        {{ reply.user.name }}
                    </div>
                    <div class="text-sm">
                        {{ reply.reply }}
                    </div>
                </div>
            </div>
            <div class="flex">
                <div class="px-3 items-center flex">
                    <span class="md:text-sm text-xs">
                        {{ getDateTime(reply.created_at) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RepliesList",
        props: [
            'reply'
        ],
        computed: {
            ftpUrl() {
                return this.$store.state.ftpUrl
            },
        },
        methods: {
            getDateTime (dateTime) {
                return moment(dateTime).fromNow();
            },

            getReplyProfilePhoto (user) {
                if (user.profile_photo_path) {
                    return this.ftpUrl + user.profile_photo_path
                } else {
                    return user.profile_photo_url
                }
            },
        }
    }
</script>

<style scoped>

</style>
