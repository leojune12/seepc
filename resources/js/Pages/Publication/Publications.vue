<template>
    <app-layout>
        <div
            class="flex flex-wrap justify-center md:justify-evenly"
        >
            <div
                v-if="!!user_profile"
                class="xl:w-96 pb-0 pt-4 container profile-container mb-6 md:mb-0"
            >
                <profile-card
                    :user="user_profile.data"
                />
            </div>

            <div
                class="container card-container pt-0 md:pt-4 pb-6 space-y-6 md:space-y-4 flex-none"
                :class="[{ 'pt-4' : !$page.props.user }, { 'xl:mt-24' : !!user_profile }]"
            >
                <publish-button v-if="showPublishButton" />

                <publication-card
                    v-for="publication in publicationsFromStore"
                    v-show="publication"
                    :publication="publication"
                    :key="publication.id"
                    :in_user_profile="!!user_profile"
                />

                <infinite-loading
                    :identifier="userStatusUpdate"
                    @infinite="infiniteHandler"
                >
                    <div slot="spinner">
                        <publication-skeleton-card />
                    </div>
                    <div slot="no-more" class="text-gray-500 flex items-center justify-center">
                        <span>
                            No more publications
                        </span>
                    </div>
                    <div slot="no-results" class="text-gray-500 flex items-center justify-center">
                        <span>
                            No publications yet
                        </span>
                    </div>
                </infinite-loading>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import InfiniteLoading from 'vue-infinite-loading'
    import { mapActions } from 'vuex'
    import AppLayout from '@/Layouts/AppLayout'
    import PublicationCard from "@/Components/PublicationCard"
    import PublishButton from "@/Components/PublishButton"
    import PublicationSkeletonCard from "@/Components/PublicationSkeletonCard"
    import ProfileCard from "@/Components/Publication/ProfileCard"

    export default {
        name: "Publications",
        components: {
            AppLayout,
            PublicationCard,
            PublishButton,
            InfiniteLoading,
            PublicationSkeletonCard,
            ProfileCard
        },
        props: {
            in_user_profile: {
                type: Boolean,
                default: false
            },

            user_profile: {
                type: Object,
                default: null
            }
        },
        computed: {
            publicationsFromStore: {
                get: function () {
                    return this.$store.state.publications
                },
                set: function (value) {
                    this.setPublications(value)
                }
            },

            scrollPublications () {
                return this.$store.state.scrollPublications
            },

            lastShowedPublicationId () {
                return this.$store.state.lastShowedPublicationId
            },

            userAuthorization () {
                return this.$store.state.userAuthorization
            },

            getIds () {
                let ids = []
                this.publicationsFromStore.forEach((item, index) => {
                    ids.push(item.id)
                })
                return ids
            },

            userStatus () {
                return !!this.$page.props.user
            },

            user_profile_id () {
                return !!this.user_profile ? this.user_profile.data.id : null
            },

            showPublishButton () {
                if (!!this.user_profile) {
                    if (!!this.$page.props.user) {
                        return this.$page.props.user.id === this.user_profile.data.id
                    } else {
                        return false
                    }
                } else {
                    return !!this.$page.props.user
                }
            },

            currentRoute () {
                return this.$store.state.currentRoute
            },

        },

        watch: {
            userStatus: function () {
                this.resetPublications()
            }
        },

        data () {
            return {
                userStatusUpdate: Date.now()
            }
        },

        mounted () {
            // check if to reset publications
            this.checkRoute()

            // scroll to center of publication image
            this.scroll()

            // check if user changed authorization(logged in or logged out)
            this.checkUser()

            // add event listeners to pusher channel
            this.listenForUpdates()
        },

        created () {
            this.scrollToTop()
        },

        methods: {
            ...mapActions([
                'setPublications',
                'setScrollPublications',
                'setPublicationLikes',
                'addPublicationComment',
                'addPublicationCommentReply',
                'updateUserAuthorization',
                'deletePublication',
                'setCurrentRoute'
            ]),

            scroll () {
                let publicationCard = document.getElementById('publication_' + this.lastShowedPublicationId)

                if (publicationCard) {
                    Promise.resolve().then(() => {
                        publicationCard.scrollIntoView({block: "center"})
                    })
                }
            },

            infiniteHandler($state) {
                let firstItem = {
                    created_at: null
                }

                if (this.publicationsFromStore.length) {
                    firstItem = this.publicationsFromStore[0]
                }

                axios.post(this.route('publications.get-publications'), {
                    publications_ids: this.getIds,
                    first_item_created_at: firstItem.created_at,
                    //in_user_profile: this.in_user_profile,
                    user_profile_id: this.user_profile_id
                })
                    .then(response => {
                        if (response.data.publications.length) {
                            this.publicationsFromStore.push(...response.data.publications)
                            $state.loaded()
                        } else {
                            $state.complete()
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            listenForUpdates () {
                Echo.channel('publications')
                    .listen('PublicationLiked', (incomingData) => {
                        let data = {
                            currentUserId: this.$page.props.user ? this.$page.props.user.id : null,
                            data: incomingData
                        }
                        this.setPublicationLikes(data)
                    })
                    .listen('PublicationCommentAdded', (incomingData) => {
                        this.addPublicationComment(incomingData)
                    })
                    .listen('PublicationCommentReplyAdded', (incomingData) => {
                        this.addPublicationCommentReply(incomingData)
                    })
                    .listen('PublicationDeleted', (incomingData) => {
                        this.deletePublication(incomingData.publication_id)
                    })
            },

            scrollToTop () {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            },

            async resetPublications () {
                // wait until finish updating values
                await new Promise(resolve => {
                    this.setPublications([])
                    this.updateUserAuthorization({
                        firstVisit: false,
                        status: !!this.$page.props.user
                    })
                    resolve()
                })

                await new Promise(resolve => {
                    // update value to reset the vue infinite loader component
                    this.userStatusUpdate = Date.now()

                    resolve()
                })
            },

            checkUser () {
                if (this.userAuthorization.firstVisit) {
                    this.updateUserAuthorization({
                        firstVisit: false,
                        status: !!this.$page.props.user
                    })
                } else {
                    if (this.userAuthorization.status !== !!this.$page.props.user) {
                        this.resetPublications()
                    }
                }
            },

            checkRoute () {
                // routes that are using Publications component
                const publicationRoutes = ['publications', 'my-profile', 'user.profile']

                // reset if routes has changed
                if (publicationRoutes.includes(this.route().current())) {
                    if (this.currentRoute.route !== this.route().current()) {
                        this.resetPublications()
                    }
                }

                // save current route to store
                this.setCurrentRoute({
                    route: this.route().current(),
                    params: !!this.route().params.user ? this.route().params.user : null
                })
            }
        },

        beforeDestroy() {
            Echo.leaveChannel('publications')
        }
    }
</script>

<style scoped>
    @media (min-width: 1280px) {
        .profile-container {
            width: 24rem !important;
        }

        .card-container {
            width: 31rem !important;
        }
    }

    .container {
        max-width: 31rem !important;
    }
</style>
