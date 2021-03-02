<template>
    <div>
        <div
            v-if="$page.props.user"
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
                            :placeholder="replyPlaceholder"
                            v-model="reply"
                            :disabled="sending"
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
        <replies-list
            v-for="reply in comment.replies"
            :key="reply.id"
            :reply="reply"
        />
        <comment-skeleton
            v-if="fetching"
            :reply="true"
        />
        <div
            v-if="repliesNextPageLink && !fetching"
            class="font-bold text-sm text-gray-500 cursor-pointer hover:underline inline py-2"
            @click="fetchReplies"
        >
            Show more replies
        </div>
    </div>
</template>

<script>
    import RepliesList from "@/Components/RepliesList";
    import {mapActions} from 'vuex'
    import CommentSkeleton from "@/Components/CommentSkeleton";

    export default {
        name: "CommentReplies",
        components: {
            RepliesList,
            CommentSkeleton
        },
        props: [
            'comment'
        ],
        computed: {
            ftpUrl() {
                return this.$store.state.ftpUrl
            },

            getIds () {
                let ids = []
                this.comment.replies.forEach((item, index) => {
                    ids.push(item.id)
                })
                return ids
            }
        },
        data () {
            return {
                reply: '',
                fetching: false,
                replies: [],
                replyPlaceholder: 'Reply...',
                sending: false,
                repliesNextPageLink: null
            }
        },
        mounted() {
            this.fetchReplies()
        },
        methods: {
            ...mapActions([
                'setPublicationCommentReplies',
                'addPublicationCommentReply'
            ]),

            getProfilePhoto () {
                if (this.$page.props.user.profile_photo_path) {
                    return this.ftpUrl + this.$page.props.user.profile_photo_path
                } else {
                    return this.$page.props.user.profile_photo_url
                }
            },

            isNullOrWhiteSpace (str) {
                return (!str || str.length === 0 || /^\s*$/.test(str))
            },

            saveReply () {
                let currentReply = this.reply

                if (!this.isNullOrWhiteSpace(currentReply)) {
                    this.sending = true
                    this.reply = ''
                    this.replyPlaceholder = 'Posting reply...'

                    axios.post(this.route('publications.comment.reply.store'), {
                        reply: currentReply,
                        comment_id: this.comment.id,
                    })
                        .then(response => {
                            this.addPublicationCommentReply(response)
                        })
                        .catch(error => {
                            console.log(error);
                        })
                        .finally(() => {
                            this.replyPlaceholder = 'Reply...'
                            this.sending = false
                        })
                }
            },

            fetchReplies() {
                if (this.comment.replies_count) {

                    this.fetching = true

                    axios.post(this.route('publications.comment.reply.show'), {
                        replies_ids: this.getIds,
                        comment_id: this.comment.id
                    })
                        .then(response => {
                            this.repliesNextPageLink = response.data.replies.links.next

                            this.setPublicationCommentReplies({
                                publication_id: this.comment.publication_id,
                                comment_id: this.comment.id,
                                replies: response.data.replies.data
                            })
                        })
                        .catch(error => {
                            console.log(error);
                        })
                        .finally(() => {
                            this.fetching = false
                        })
                } else {
                    this.fetching = false
                }
            },
        }
    }
</script>

<style scoped>

</style>
