<template>
    <div
        style="min-height: 32px;"
        class="flex text-gray-800 mt-1"
    >
        <div class="flex-none mr-2">
            <img
                :src="getCommentProfilePhoto(comment.user)"
                alt="profile photo"
                class="w-9 h-9 rounded-full object-cover border border-gray-300"
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
                        {{ comment.user.name }}
                    </div>
                    <div class="text-sm">
                        {{ comment.comment }}
                    </div>
                </div>
            </div>
            <div class="flex">
                <div class="px-3 items-center flex">
                    <span class="md:text-sm text-xs">
                        {{ getDateTime(comment.created_at) }}
                    </span>
                    <span
                        v-if="$page.props.user"
                        class="text-xs"
                    >&nbsp;&bullet;&nbsp;</span>
                    <span
                        v-if="$page.props.user"
                        class="md:text-sm text-xs font-semibold"
                    >
                        <span
                            class="hover:underline cursor-pointer"
                            @click="showReplies = true"
                        >
                            Reply
                        </span>
                    </span>
                </div>
            </div>
            <div
                v-if="comment.replies_count && !showReplies"
                class="font-semibold text-gray-600 text-sm flex py-1 px-3"
                @click="showReplies = true"
            >
                <div class="flex items-center cursor-pointer">
                    <div>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19,15L13,21L11.58,19.58L15.17,16H4V4H6V14H15.17L11.58,10.42L13,9L19,15Z" />
                        </svg>
                    </div>
                    <div class="hover:underline">
                        Show {{ comment.replies_count }} {{ comment.replies_count > 1 ? 'replies' : 'reply' }}
                    </div>
                </div>
            </div>
            <comment-replies
                v-if="showReplies"
                :comment="comment"
            />
        </div>
    </div>
</template>

<script>
    import CommentReplies from "@/Components/CommentReplies";

    export default {
        name: "CommentsList",
        components: {
            CommentReplies
        },
        props: [
            'comment'
        ],
        computed: {
            ftpUrl() {
                return this.$store.state.ftpUrl
            },
        },
        data () {
            return {
                showReplies: false,
                showReplyLoader: false,
            }
        },
        methods: {
            getDateTime (dateTime) {
                return moment(dateTime).fromNow();
            },

            getCommentProfilePhoto (user) {
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
