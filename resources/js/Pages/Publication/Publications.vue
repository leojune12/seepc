<template>
    <app-layout>
        <div
            class="flex justify-center tracking-tight"
        >
            <div
                class="card-container w-full pt-0 pb-6 md:pt-6 space-y-6 md:space-y-4"
                :class="{ 'pt-4' : !$page.props.user }"
                style="max-width: 31rem;"
            >
                <publish-button v-if="$page.props.user" />

                <publication-card
                    v-for="publication in publicationsFromStore"
                    v-show="publication"
                    :publication="publication"
                    :key="publication.id"
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
                </infinite-loading>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import InfiniteLoading from 'vue-infinite-loading';
    import { mapActions } from 'vuex'
    import AppLayout from '@/Layouts/AppLayout'
    import PublicationCard from "@/Components/PublicationCard";
    import PublishButton from "@/Components/PublishButton";
    import PublicationSkeletonCard from "@/Components/PublicationSkeletonCard";

    export default {
        name: "Publications",
        components: {
            AppLayout,
            PublicationCard,
            PublishButton,
            InfiniteLoading,
            PublicationSkeletonCard
        },
        props: {
            get_my_publications: {
                type: Boolean,
                default: false
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

            reloadAllPublications: {
                get () {
                    return this.$store.state.reloadAllPublications
                },
                set (payload) {
                    this.updateReloadAllPublications(payload)
                }
            },

            reloadMyPublications: {
                get () {
                    return this.$store.state.reloadMyPublications
                },
                set (payload) {
                    this.updateReloadMyPublications(payload)
                }
            }

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
            // check if to reset currently loaded publications
            this.checkPublicationsFlag()

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
                'updateReloadAllPublications',
                'updateReloadMyPublications',
                'deletePublication',
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
                    get_my_publications: this.get_my_publications
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
                        console.log(incomingData)
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

            checkPublicationsFlag () {
                // check if in all-publications page or my-publications page
                if (this.get_my_publications) {
                    // my-publications
                    if (this.reloadMyPublications) {
                        this.reloadMyPublications = false
                        this.reloadAllPublications = true

                        this.resetPublications()
                    }
                } else {
                    // all-publications
                    if (this.reloadAllPublications) {
                        this.reloadAllPublications = false
                        this.reloadMyPublications = true

                        this.resetPublications()
                    }
                }
            }

        },

        beforeDestroy() {
            Echo.leaveChannel('publications')
        }
    }
</script>

<style scoped>
    @media (min-width: 640px) {
        .card-container {
            width: 31rem !important;
        }
    }
</style>
