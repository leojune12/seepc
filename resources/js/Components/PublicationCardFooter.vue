<template>
    <div>
        <div v-if="publication.likes_count || publication.comments_count">
            <div
                class="py-2 md:text-normal text-sm flex justify-between"
                :class="{ 'px-3 md:px-0' : inShowComponent }"
            >
                <div
                    :class="[ inShowComponent ? 'text-gray-200 md:text-gray-800' : 'text-gray-800' ]"
                >
                    <span v-if="publication.likes_count">
                        {{ publication.likes_count + ' ' + likesString }}
                    </span>
                </div>
                <div
                    :class="[ inShowComponent ? 'text-gray-200 md:text-gray-800' : 'text-gray-800' ]"
                >
                    <span
                        v-if="publication.comments_count"
                        class="hover:underline cursor-pointer"
                        @click="showComments = true"
                    >
                        {{ publication.comments_count + ' ' + commentsString }}
                    </span>
                </div>
            </div>
            <div>
                <div class="border-t border-gray-300"></div>
            </div>
        </div>
        <div class="flex py-1">
            <button
                class="h-9 rounded focus:outline-none flex-1 text-center md:hover:bg-gray-100"
                :disabled="disabled"
                @click="like"
            >
                <div class="flex justify-center">
                    <div
                        v-if="inShowComponent"
                        class="flex"
                        :class="[ publication.liked ? 'text-blue-500' : 'text-gray-300 md:text-gray-500' ]"
                    >
                        <div class="mr-2">
                            <svg v-if="publication.liked" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z" />
                            </svg>
                            <svg v-else style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5,9V21H1V9H5M9,21A2,2 0 0,1 7,19V9C7,8.45 7.22,7.95 7.59,7.59L14.17,1L15.23,2.06C15.5,2.33 15.67,2.7 15.67,3.11L15.64,3.43L14.69,8H21C22.11,8 23,8.9 23,10V12C23,12.26 22.95,12.5 22.86,12.73L19.84,19.78C19.54,20.5 18.83,21 18,21H9M9,19H18.03L21,12V10H12.21L13.34,4.68L9,9.03V19Z" />
                            </svg>
                        </div>
                        <span class="font-bold" style="font-size: 15px;">Like</span>
                    </div>
                    <div
                        v-else
                        class="flex"
                        :class="[ publication.liked ? 'text-blue-500' : 'text-gray-500' ]"
                    >
                        <div class="mr-2">
                            <svg v-if="publication.liked" style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z" />
                            </svg>
                            <svg v-else style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5,9V21H1V9H5M9,21A2,2 0 0,1 7,19V9C7,8.45 7.22,7.95 7.59,7.59L14.17,1L15.23,2.06C15.5,2.33 15.67,2.7 15.67,3.11L15.64,3.43L14.69,8H21C22.11,8 23,8.9 23,10V12C23,12.26 22.95,12.5 22.86,12.73L19.84,19.78C19.54,20.5 18.83,21 18,21H9M9,19H18.03L21,12V10H12.21L13.34,4.68L9,9.03V19Z" />
                            </svg>
                        </div>
                        <span class="font-bold" style="font-size: 15px;">Like</span>
                    </div>
                </div>
            </button>
            <button
                class="h-9 rounded md:hover:bg-gray-100 focus:outline-none flex-1 text-center"
                @click="showComments = true"
            >
                <div class="flex justify-center">
                    <div
                        class="flex"
                        :class="[ inShowComponent ? 'text-gray-300 md:text-gray-500' : 'text-gray-500' ]"
                    >
                        <div class="mr-2">
                            <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z" />
                            </svg>
                        </div>
                        <span class="font-bold" style="font-size: 15px;">Comment</span>
                    </div>
                </div>
            </button>
        </div>
        <publication-comments
            v-if="showComments"
            :publication="publication"
            :in-show-component="inShowComponent"
            v-on:hide-comments="showComments = false"
        />
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import PublicationComments from "@/Components/PublicationComments";

    export default {
        name: "PublicationCardFooter",
        components: {
            PublicationComments
        },
        props: {
            publication: Object,
            inShowComponent: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                disabled: false,
                showComments: false
            }
        },
        computed: {
            likesString () {
                return this.publication.likes_count > 1 ? 'Likes' : 'Like'
            },

            commentsString () {
                return this.publication.comments_count > 1 ? 'Comments' : 'Comment'
            },

            publications () {
                return this.$store.state.publications
            }
        },
        mounted () {
        },
        methods: {
            ...mapActions([
                'setScrollPublications',
                'setLoginMessage'
            ]),
            like() {
                this.disabled = true
                if (!this.$page.props.user) {
                    this.setLoginMessage('You must sign in to perform this action.')
                    this.$inertia.get(route('login'))
                } else {
                    if (this.publication.liked) {
                        // like publication
                        this.publication.liked = false
                        axios.post(this.route('publications.unlike'), {
                            user_id: this.$page.props.user.id,
                            publication_id: this.publication.id
                        })
                            .then(response => {
                                this.publication.likes_count = response.data.likes_count
                                this.disabled = false
                            })
                            .catch(error => {
                                console.log(error);
                                this.disabled = false
                                this.publication.liked = true
                            });
                    } else {
                        // unlike publication
                        this.publication.liked = true
                        axios.post(this.route('publications.like'), {
                            user_id: this.$page.props.user.id,
                            publication_id: this.publication.id
                        })
                            .then(response => {
                                this.publication.likes_count = response.data.likes_count
                                this.disabled = false
                            })
                            .catch(function (error) {
                                console.log(error);
                                this.disabled = false
                                this.publication.liked = true
                            });
                    }
                    this.setScrollPublications(true)
                }
            },
        },
    }
</script>

<style scoped>

</style>
