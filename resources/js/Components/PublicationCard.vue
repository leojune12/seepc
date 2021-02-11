<template>
    <div class="md:border-l md:border-r border-t border-b bg-white shadow rounded-none md:rounded-xl px-3 md:px-4">
        <div>
            <div class="flex flex-row pt-3 items-center">
                <div class="mr-2">
                    <img
                        :src="getProfilePhoto()"
                        alt="profile photo"
                        class="w-10 h-10 rounded-full object-cover border border-gray-300"
                    >
                </div>
                <div>
                    <div
                        class="font-bold text-gray-800"
                    >
                        {{ publication.user.name }}
                    </div>
                    <div
                        class="text-sm font-semibold text-gray-500"
                    >
                        {{ getDateTime(publication.created_at) }}
                    </div>
                </div>
            </div>
            <div class="py-3 space-y-3 text-gray-800">
                <div class="md:text-base text-sm">
                    <div v-for="description in descriptionArray" class="leading-tight">
                        {{ description.length ? description : '&nbsp;' }}
                    </div>
                </div>

                <div v-show="!isSpecsEmpty()" class="flex flex-col">
                    <ul class="space-y-1 md:text-base text-xs">
                        <li v-for="specs in specsNames()" :class="{ hidden : publication.specifications[specs] === null }">
                            <span class="font-bold uppercase mr-2">
                                {{ specs }}:
                            </span>
                            <span>
                                {{ publication.specifications[specs] }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div>
            <img
                :id="publication.id"
                :src="ftpUrl+publication.photo_path"
                alt="image"
                class="w-full h-96 object-cover md:cursor-pointer rounded-xl border"
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

    export default {
        name: "PublicationCard",
        components: {
            PublicationCardFooter
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
