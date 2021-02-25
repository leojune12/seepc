<template>
    <app-layout>
        <div class="flex justify-center tracking-tight">
            <div
                class="md:space-y-6 space-y-4 card-container w-full pt-0 pb-6 md:pt-6"
                :class="{ 'pt-4' : !$page.props.user }"
                style="max-width: 31rem;"
            >
                <publish-button v-if="$page.props.user" />

                <publication-card
                    v-for="publication in publicationsFromStore"
                    :publication="publication"
                    :key="publication.id"
                />

                <infinite-loading @infinite="infiniteHandler" spinner="waveDots">
                    <div slot="no-more" class="text-gray-500 h-16 flex items-center justify-center">
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

    export default {
        name: "Publications",
        components: {
            AppLayout,
            PublicationCard,
            PublishButton,
            InfiniteLoading
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
            }
        },

        mounted () {
            this.scroll()
            this.listenForUpdates()
        },

        methods: {
            ...mapActions([
                'setPublications',
                'setScrollPublications',
                'setPublicationsPage',
                'setPublicationLikes',
                'setPublicationComments',
                'addPublicationComment'
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
                    page: this.page,
                    first_item_created_at: firstItem.created_at
                })
                    .then(response => {
                        if (response.data.publications.length) {
                            this.page++
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
                            currentUserId: this.$page.props.user.id,
                            data: incomingData
                        }
                        this.setPublicationLikes(data)
                    })
                    .listen('PublicationCommentAdded', (incomingData) => {
                        this.addPublicationComment(incomingData)
                    })
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
