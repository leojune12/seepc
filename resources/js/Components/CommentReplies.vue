<template>
    <div
        class="flex h-9 mt-1"
    >
        <div class="flex-none mr-2 flex items-center">
            <img
                :src="getProfilePhoto()"
                alt="profile photo"
                class="w-7 h-7 rounded-full object-cover border border-gray-300"
            >
        </div>
        <div class="flex-1 bg-gray-100 rounded-2xl flex items-center">
            <div class="flex-1">
                <form @submit.prevent="saveReply">
                    <input
                        type="text"
                        class="w-full border-none focus:ring-0 focus:border-none px-3 resize-none overflow-y-hidden py-1 bg-transparent text-sm"
                        placeholder="Reply..."
                        v-model="reply"
                    >
                </form>
            </div>
            <div
                class="flex-none text-blue-500 pr-3 md:hidden block"
            >
                <span
                    class="cursor-pointer"
                    @click="saveReply"
                >
                    <svg
                        style="width:24px;height:24px"
                        viewBox="0 0 24 24"
                    >
                        <path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CommentReplies",
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
                reply: '',
            }
        },
        methods: {
            getProfilePhoto () {
                if (this.$page.props.user.profile_photo_path) {
                    return this.ftpUrl + this.$page.props.user.profile_photo_path
                } else {
                    return this.$page.props.user.profile_photo_url
                }
            },

            getReplyProfilePhoto (user) {
                if (user.profile_photo_path) {
                    return this.ftpUrl + user.profile_photo_path
                } else {
                    return user.profile_photo_url
                }
            },

            saveReply () {
                axios.post(this.route('publications.comment.store'), {
                    user_id: this.$page.props.user.id,
                    reply: this.reply,
                    publication_id: this.publication.id,
                    currentPublication: null,
                })
                    .then(response => {
                        this.reply = ''
                        this.$emit('update-comment-count', response.data.comment_count)
                        console.log(response)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            fetchComments() {
                this.showCommentLoader = true
                axios.post(this.route('publications.comment.show'), {
                    page: this.publication.comment_page,
                    publication_id: this.publication.id
                })
                    .then(response => {
                        this.showCommentLoader = false
                        this.showCommentInput = true
                        if (response.data.comments) {
                            this.publication.comment_page++
                            this.publication.comments.push(...response.data.comments)
                        }
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
