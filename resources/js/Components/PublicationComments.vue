<template>
    <div>
        <!-- Mobile View -->
        <div v-if="window.width < 768 && inShowComponent">
            <transition
                enter-active-class="transform transition ease-in duration-300"
                enter-class="translate-y-full"
                enter-to-class="translate-y-0"
                leave-active-class="transform transition ease-in duration-300"
                leave-class="translate-y-0"
                leave-to-class="translate-y-full"
            >
                <div
                    class="h-screen overflow-y-auto bg-white pt-14"
                    v-show="animate"
                >
                    <div>
                        <div class="flex justify-between items-center pt-4 pb-3 border-b sticky top-0 left-0 px-3 border-t border-b border-gray-300 bg-white w-full text-gray-800">
                            <div class="flex items-center">
                                <span
                                    class="rounded-full hover:bg-gray-100 w-9 h-9 flex justify-center items-center mr-2"
                                    v-on:click="$emit('hide-comments')"
                                >
                                    <!-- left arrow -->
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
                                    </svg>
                                </span>
                                <span>Comments</span>
                            </div>
                            <div
                                v-if="publication.comments_count"
                                class="flex items-center"
                            >
                                <span class="text-gray-500 mr-1">
                                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M5,5V7H19V5H5M5,9V11H13V9H5M5,13V15H15V13H5Z" />
                                    </svg>
                                </span>
                                <span>{{ publication.comments_count }}</span>
                            </div>
                        </div>
                        <div
                            v-if="$page.props.user"
                            class="flex items-center h-9 fixed bottom-0 left-0 bg-white h-14 w-full border-t px-3"
                        >
                            <div class="flex-none mr-2">
                                <img
                                    :src="getProfilePhoto()"
                                    alt="profile photo"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-300"
                                >
                            </div>
                            <div class="flex-1 h-9 bg-gray-100 rounded-full flex items-center">
                                <div class="flex-1">
                                    <form @submit.prevent="saveComment">
                                        <input
                                            type="text"
                                            class="w-full border-none focus:ring-0 focus:border-none px-3 resize-none overflow-y-hidden py-1 bg-transparent text-sm"
                                            :placeholder="commentPlaceholder"
                                            v-model="comment"
                                            :disabled="sending"
                                        >
                                    </form>
                                </div>
                                <div
                                    class="flex-none text-blue-500 pr-3"
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
                        <div class="px-3">
                            <comments-list
                                v-for="comment in publication.comments"
                                :key="comment.id"
                                :comment="comment"
                            />
                        </div>
                        <div
                            v-if="fetching"
                            class="px-3"
                        >
                            <comment-skeleton />
                        </div>
                        <div
                            v-if="commentsNextPageLink && !fetching"
                            class="font-bold text-sm text-gray-500 cursor-pointer hover:underline inline p-3"
                            @click="fetchComments"
                        >
                            Show more comments
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <!-- Desktop View -->
        <div v-else>
            <div
                v-if="$page.props.user || publication.comments_count"
                class="border-t border-gray-300"
            ></div>
            <div
                v-if="$page.props.user"
                class="flex h-9 mt-3"
                :class="{ 'mb-3' : !publication.comments_count }"
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
                                :placeholder="commentPlaceholder"
                                v-model="comment"
                                :disabled="sending"
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
            <div
                v-if="publication.comments_count"
                class="mb-3"
                :class="{'mt-3' : !$page.props.user}"
            >
                <comments-list
                    v-for="comment in publication.comments"
                    :key="comment.id"
                    :comment="comment"
                />
                <div v-if="fetching">
                    <comment-skeleton />
                </div>
                <div
                    v-if="commentsNextPageLink && !fetching"
                    class="font-bold text-sm text-gray-500 cursor-pointer hover:underline inline"
                    @click="fetchComments"
                >
                    Show more comments
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentsList from "@/Components/CommentsList";
    import {mapActions} from 'vuex'
    import CommentSkeleton from "@/Components/CommentSkeleton";

    export default {
        name: "PublicationComments",
        components: {
            CommentsList,
            CommentSkeleton
        },
        props: {
            publication: {
                type: Object
            },
            inShowComponent: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            publications () {
                return this.$store.state.publications
            },

            getIds () {
                let ids = []
                this.publication.comments.forEach((item, index) => {
                    ids.push(item.id)
                })
                return ids
            }
        },
        data () {
            return {
                animate: false,
                comment: '',
                fetching: false,
                sending: false,
                commentPlaceholder: 'Comment...',
                window: {
                    width: 0,
                    height: 0
                },
                commentsNextPageLink: null,
            }
        },
        created() {
            window.addEventListener('resize', this.handleResize);
            this.handleResize();
        },
        mounted () {
            this.fetchComments(this.initialCommentsLink)
            this.animate = true
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

            handleResize() {
                this.window.width = window.innerWidth;
                this.window.height = window.innerHeight;
            },

            isNullOrWhiteSpace (str) {
                return (!str || str.length === 0 || /^\s*$/.test(str))
            },

            saveComment () {
                let currentComment = this.comment

                if (!this.isNullOrWhiteSpace(currentComment)) {
                    this.sending = true
                    this.comment = ''
                    this.commentPlaceholder = 'Posting comment...'

                    axios.post(this.route('publications.comment.store'), {
                        comment: currentComment,
                        publication_id: this.publication.id,
                    })
                        .then(response => {
                            this.addPublicationComment(response)
                        })
                        .catch(error => {
                            console.log(error);
                        })
                        .finally(() => {
                            this.commentPlaceholder = 'Comment...'
                            this.sending = false
                        })
                }
            },

            fetchComments () {
                if (this.publication.comments_count) {

                    this.fetching = true

                    axios.post(this.route('publications.comment.show'), {
                        comments_ids: this.getIds,
                        publication_id: this.publication.id,
                    })
                        .then(response => {
                            this.commentsNextPageLink = response.data.comments.links.next

                            let data = {
                                publication_id: this.publication.id,
                                comments: response.data.comments.data,
                            }
                            this.setPublicationComments(data)
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                        .finally(() => {
                            this.fetching = false
                        })

                } else {
                    this.fetching = false
                }
            },
        },
        destroyed() {
            window.removeEventListener('resize', this.handleResize);
        },
    }
</script>

<style scoped>

</style>
