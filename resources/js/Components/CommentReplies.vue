<template>
    <div>
        <div
            v-if="showReplyLoader"
            class="py-2 px-5"
        >
            <svg
                width="50"
                height="12"
                viewBox="0 0 120 30"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-current text-gray-500"
            >
                <circle cx="15" cy="15" r="15">
                    <animate attributeName="r" from="15" to="15"
                             begin="0s" dur="0.8s"
                             values="15;9;15" calcMode="linear"
                             repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" from="1" to="1"
                             begin="0s" dur="0.8s"
                             values="1;.5;1" calcMode="linear"
                             repeatCount="indefinite" />
                </circle>
                <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                    <animate attributeName="r" from="9" to="9"
                             begin="0s" dur="0.8s"
                             values="9;15;9" calcMode="linear"
                             repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" from="0.5" to="0.5"
                             begin="0s" dur="0.8s"
                             values=".5;1;.5" calcMode="linear"
                             repeatCount="indefinite" />
                </circle>
                <circle cx="105" cy="15" r="15">
                    <animate attributeName="r" from="15" to="15"
                             begin="0s" dur="0.8s"
                             values="15;9;15" calcMode="linear"
                             repeatCount="indefinite" />
                    <animate attributeName="fill-opacity" from="1" to="1"
                             begin="0s" dur="0.8s"
                             values="1;.5;1" calcMode="linear"
                             repeatCount="indefinite" />
                </circle>
            </svg>
        </div>
        <div
            v-else
        >
            <replies-list
                v-for="reply in comment.replies"
                :key="reply.id"
                :reply="reply"
            />
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
        </div>
    </div>
</template>

<script>
    import RepliesList from "@/Components/RepliesList";
    import {mapActions} from 'vuex'

    export default {
        name: "CommentReplies",
        components: {
            RepliesList
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
                reply: '',
                showReplyLoader: true,
                replies: []
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
                if (!this.isNullOrWhiteSpace(this.reply)) {
                    axios.post(this.route('publications.comment.reply.store'), {
                        reply: this.reply,
                        comment_id: this.comment.id,
                    })
                        .then(response => {
                            this.reply = ''
                            //this.replies.push(response.data.reply)
                            //console.log(response)
                            this.addPublicationCommentReply(response)
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },

            fetchReplies() {
                if (this.comment.replies_count) {
                    axios.post(this.route('publications.comment.reply.show'), {
                        comment_id: this.comment.id
                    })
                        .then(response => {
                            this.showReplyLoader = false
                            this.setPublicationCommentReplies({
                                publication_id: this.comment.publication_id,
                                comment_id: this.comment.id,
                                replies: response.data.replies
                            })
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {
                    this.showReplyLoader = false
                }
            },
        }
    }
</script>

<style scoped>

</style>
