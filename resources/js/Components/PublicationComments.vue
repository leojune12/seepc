<template>
    <div>
        <div class="border-t border-gray-300"></div>
        <div class="py-3">
            <div
                v-if="showCommentInput && $page.props.user"
                class="flex h-9"
            >
                <div class="flex-none mr-2">
                    <img
                        :src="getProfilePhoto()"
                        alt="profile photo"
                        class="w-9 h-9 rounded-full object-cover border border-gray-300"
                    >
                </div>
                <div class="flex-1 bg-gray-100 rounded-2xl flex items-center">
                    <div class="flex-1">
                        <form @submit.prevent="saveComment">
                            <input
                                type="text"
                                class="w-full border-none focus:ring-0 focus:border-none px-3 resize-none overflow-y-hidden py-1 bg-transparent text-sm"
                                placeholder="Comment..."
                                v-model="comment"
                            >
                        </form>
                    </div>
                    <div
                        class="flex-none text-blue-500 pr-3 md:hidden block"
                    >
                        <span
                            class="cursor-pointer"
                            @click="saveComment"
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
            <div class="mt-2">
                <comments-list
                    v-for="comment in publication.comments"
                    :key="comment.id"
                    :comment="comment"
                />
                <div
                    v-if="showCommentLoader"
                    class="flex justify-center py-2"
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
            </div>
        </div>
    </div>
</template>

<script>
    import CommentsList from "@/Components/CommentsList";
    import {mapActions} from 'vuex'

    export default {
        name: "PublicationComments",
        components: {
            CommentsList,
        },
        props: {
            publication: {
                type: Object
            },
        },
        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            publications () {
                return this.$store.state.publications
            }
        },
        data () {
            return {
                comment: '',
                showCommentLoader: false,
                showCommentInput: false,
                comments: [],
            }
        },
        mounted () {
            this.fetchComments()
        },
        methods: {
            ...mapActions([
                'setPublicationComments',
                'addPublicationComment'
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

            saveComment () {
                if (!this.isNullOrWhiteSpace(this.comment)) {
                    axios.post(this.route('publications.comment.store'), {
                        comment: this.comment,
                        publication_id: this.publication.id,
                    })
                        .then(response => {
                            this.comment = ''
                            this.addPublicationComment(response)
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },

            fetchComments() {
                if (this.publication.comments_count) {

                    this.showCommentLoader = true

                    axios.post(this.route('publications.comment.show'), {
                        publication_id: this.publication.id
                    })
                        .then(response => {
                            this.showCommentLoader = false
                            this.showCommentInput = true

                            let data = {
                                publication_id: this.publication.id,
                                comments: response.data.comments
                            }
                            this.setPublicationComments(data)
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                } else {
                    this.showCommentInput = true
                }
            },
        }
    }
</script>

<style scoped>

</style>
