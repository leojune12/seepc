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
            <div class="px-3 items-center flex">
                <span class="md:text-sm text-xs">
                    {{ getDateTime(comment.created_at) }}
                </span>
                <span class="text-xs">&nbsp;&bullet;&nbsp;</span>
                <span class="md:text-sm text-xs font-semibold">
                    <span
                        class="hover:underline cursor-pointer"
                        @click="showReplyInput = true"
                    >
                        Reply
                    </span>
                </span>
            </div>
            <comment-replies
                v-if="showReplyInput"
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
                showReplyInput: false,
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

            saveComment () {
                axios.post(this.route('publications.comment.store'), {
                    user_id: this.$page.props.user.id,
                    comment: this.comment,
                    publication_id: this.publication.id,
                    currentPublication: null,
                })
                    .then(response => {
                        this.comment = ''
                        this.$emit('update-comment-count', response.data.comment_count)
                        console.log(response)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        }
    }
</script>

<style scoped>

</style>
