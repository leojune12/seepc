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
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { mapActions } from 'vuex'
    import AppLayout from '@/Layouts/AppLayout'
    import PublicationCard from "@/Components/PublicationCard";
    import PublishButton from "@/Components/PublishButton";

    export default {
        name: "Publications",
        components: {
            AppLayout,
            PublicationCard,
            PublishButton
        },
        props: [
            'publicationsFromServer',
        ],
        created() {
            this.storePublications()
        },
        computed: {
            publicationsFromStore () {
                return this.$store.state.publications
            },

            scrollPublications () {
                return this.$store.state.scrollPublications
            },

            lastShowedPublicationId () {
                return this.$store.state.lastShowedPublicationId
            }
        },
        mounted() {
            if (this.scrollPublications) {
                this.scroll()
            }
        },
        data () {
            return {
                publications: this.publicationsFromServer.data
            }
        },
        methods: {
            ...mapActions([
                'setPublications',
                'setScrollPublications'
            ]),

            scroll () {
                let promise = new Promise(function(myResolve, myReject) {
                    myResolve();
                });

                promise
                    .then(() => {
                        this.setScrollPublications(false)
                    })
                    .then(() => {
                        if (this.lastShowedPublicationId) {
                            document.getElementById(this.lastShowedPublicationId).scrollIntoView({block: "center"})
                        }
                    })
            },

            storePublications () {
                this.setPublications(this.publicationsFromServer.data)
            }
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
