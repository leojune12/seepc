<template>
    <div class="md:border-l md:border-r border-t border-b bg-white shadow rounded-none md:rounded-xl px-3 md:px-4">
        <publication-descriptions :publication="publication" />
        <div>
            <img
                :id="'publication_'+publication.id"
                :src="ftpUrl+publication.photo_path"
                alt="image"
                class="w-full h-80 object-cover md:cursor-pointer rounded-xl border"
                @click="visitPublication(publication.id)"
            >
        </div>
        <div>
            <publication-card-footer :publication="publication" />
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import PublicationCardFooter from "@/Components/PublicationCardFooter";
    import PublicationDescriptions from "@/Components/PublicationDescriptions";

    export default {
        name: "PublicationCard",
        components: {
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

            descriptionArray () {
                return this.publication.description.split('\n')
            }
        },
        data () {
            return {
            }
        },
        mounted () {
        },
        methods: {
            ...mapActions([
                'setLastShowedPublicationId'
            ]),

            getProfilePhoto () {
                if (this.publication.user.profile_photo_path) {
                    return this.ftpUrl + this.publication.user.profile_photo_path
                } else {
                    return this.publication.user.profile_photo_url
                }
            },

            getDateTime (dateTime) {
                return moment(dateTime).fromNow();
            },

            visitPublication (id) {
                let promise = new Promise(function(myResolve, myReject) {
                    myResolve();
                });

                promise
                    .then(() => {
                        this.setLastShowedPublicationId(this.publication.id)
                    })
                    .then(() => {
                        this.$inertia.get('/publications/show/'+id)
                    })
            },

            specsNames () {
                return Object.keys(this.publication.specifications)
            },

            isSpecsEmpty () {
                let isEmpty = true
                let specs

                for (specs of this.specsNames()) {
                    if (this.publication.specifications[specs] !== null) {
                        isEmpty = false
                        break
                    }
                }

                return isEmpty
            }
        }
    }
</script>

<style scoped>

</style>
