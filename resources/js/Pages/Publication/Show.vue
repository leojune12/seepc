<template>
    <div class="flex flex-col md:flex-row h-screen tracking-tight">
        <div class="md:flex-grow bg-black">
            <span
                class="absolute top-5 md:top-8 left-5 md:left-8 rounded-full bg-gray-200 hover:bg-gray-300 md:h-10 md:w-10 w-8 h-8 flex justify-center items-center md:cursor-pointer"
                @click="goBack"
            >
                <!-- left arrow -->
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
                </svg>
            </span>
            <img :src="ftpUrl+currentPublication.photo_path" alt="photo" class="w-full h-screen object-contain" @click="toggleShowDescription">
        </div>
        <div
            :class="{ 'hidden md:block' : !showDescription }"
            class="w-full md:w-96 md:flex-none md:relative absolute bottom-0 bg-opacity-50 md:bg-opacity-100 bg-black md:bg-white md:overflow-y-auto"
        >
            <!-- Descriptions -->
            <publication-descriptions
                :publication="currentPublication"
                :in-show-component="true"
                class="px-3 md:px-4"
            />
            <!-- Actions -->
            <div class="md:mt-3 mt-0">
                <div class="border-t md:border-gray-300 border-gray-100 md:mx-4"></div>
                <publication-card-footer
                    class="md:mx-4"
                    :publication="currentPublication"
                    :in-show-component="true"
                />
                <div class="border-t md:border-gray-300 border-gray-100 md:mx-4"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import PublicationCardFooter from "@/Components/PublicationCardFooter"
    import PublicationCard from "@/Components/PublicationCard"
    import PublicationDescriptions from "@/Components/PublicationDescriptions"
    import { mapActions } from 'vuex'

    export default {
        components: {
            PublicationCard,
            PublicationCardFooter,
            PublicationDescriptions
        },
        props: [
            'publication'
        ],
        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            publicationsFromStore: {
                get: function () {
                    return this.$store.state.publications
                },
                set: function (value) {
                    this.setPublications(value)
                }
            },

            currentPublication () {
                if (this.publicationsFromStore.length) {
                    const index = this.publicationsFromStore.findIndex(pub => pub.id === this.publication.data.id)
                    return this.publicationsFromStore[index]
                } else {
                    this.setPublicationShow(this.publication.data)
                    return this.publication.data
                }
            },

            previousRoute () {
                return this.$store.state.currentRoute
            }
        },

        data () {
            return {
                showDescription: true,
            }
        },

        mounted() {
            this.listenForUpdates()
        },

        methods: {
            ...mapActions([
                'setPublications',
                'setPublicationLikes',
                'setPublicationShow',
                'addPublicationComment',
                'addPublicationCommentReply'
            ]),

            goBack() {
                if (!!this.previousRoute.route) {
                    if (!!this.previousRoute.params) {
                        this.$inertia.get(this.route(this.previousRoute.route, this.previousRoute.params))
                    } else {
                        this.$inertia.get(this.route(this.previousRoute.route))
                    }
                } else {
                    this.$inertia.get(this.route('publications'))
                }
            },

            toggleShowDescription () {
                setTimeout(() => {
                    this.showDescription = !this.showDescription
                }, 200)
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
        },

        beforeDestroy() {
            Echo.leaveChannel('publications')
        }
    }
</script>

<style scoped>

</style>
