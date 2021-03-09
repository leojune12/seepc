<template>
    <transition
        enter-active-class=""
        enter-class=""
        enter-to-class=""
        leave-active-class="ease-in duration-500"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="publication"
            class="md:border-l md:border-r border-t border-b bg-white shadow rounded-none md:rounded-xl px-3 md:px-4"
        >
            <publication-descriptions
                :publication="publication"
                :in_user_profile="in_user_profile"
            />
            <div class="relative">
                <div class="w-full h-80 animate-pulse">
                    <div class="w-full h-80 bg-gray-300 rounded-xl"></div>
                </div>
                <div class="w-full h-80 absolute top-0 left-0 rounded-xl">
                    <img
                        :id="'publication_'+publication.id"
                        :src="ftpUrl+publication.photo_path"
                        alt=""
                        class="w-full h-80 object-cover md:cursor-pointer rounded-xl border"
                        @click="visitPublication(publication.id)"
                    >
                </div>
            </div>
            <div>
                <publication-card-footer :publication="publication" />
            </div>
        </div>
    </transition>
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
        props: {
            publication: {
                type: Object
            },

            in_user_profile: {
                type: Boolean,
                default: false
            },
        },
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
