<template>
    <app-layout>
        <div
            class="flex justify-center tracking-tight"
        >
            <div
                class="md:space-y-6 space-y-4 card-container w-full pt-0 pb-6 md:pt-6"
                :class="{ 'pt-4' : !$page.props.user }"
                style="max-width: 31rem;"
            >
                <publish-button v-if="$page.props.user" />

                <publication-card
                    v-if="publicationsFromStore.length"
                    v-for="publication in publicationsFromStore"
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
        computed: {
            publicationsFromStore: {
                get: function () {
                    return this.$store.state.publications
                },
                set: function (value) {
                    this.setPublications(value)
                }
            },

            page: {
                get: function () {
                    return this.$store.state.publicationsPage
                },
                set: function (value) {
                    this.setPublicationsPage(value)
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
            }
        },

        watch: {
            userStatus: function () {
                this.resetPublications()
            }
        },

        data () {
            return {
                userStatusUpdate: !!this.$page.props.user
            }
        },

        mounted () {
            // scroll to center of publication image
            this.scroll()

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
                'setPublicationsPage',
                'setPublicationLikes',
                'addPublicationComment',
                'addPublicationCommentReply',
                'updateUserAuthorization'
            ]),

            scroll () {
                let promise = new Promise(function(myResolve, myReject) {
                    myResolve();
                });

                promise
                    .then(() => {
                        if (this.lastShowedPublicationId) {
                            document.getElementById('publication_'+this.lastShowedPublicationId).scrollIntoView({block: "center"})
                        }
                    })
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
                    first_item_created_at: firstItem.created_at
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
            },

            scrollToTop () {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            },

            async resetPublications () {
                // wait until finish updating values
                await new Promise((resolve) => {
                    this.setPublications([])
                    this.updateUserAuthorization({
                        firstVisit: false,
                        status: !!this.$page.props.user
                    })
                    resolve()
                })

                // update value to reset the vue infinite loader component
                this.userStatusUpdate = !!this.$page.props.user
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
