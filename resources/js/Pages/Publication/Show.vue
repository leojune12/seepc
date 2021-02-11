<template>
    <div class="flex flex-col md:flex-row min-h-screen tracking-tight">
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
            <img :src="ftpUrl+publication.data.photo_path" alt="photo" class="w-full h-screen object-contain" @click="showDescription = !showDescription">
        </div>
        <div :class="{ 'hidden md:block' : !showDescription }" class="w-full md:w-96 md:flex-none md:relative absolute bottom-0 bg-opacity-50 md:bg-opacity-100 bg-black md:bg-white px-3 md:px-4">
            <div class="">
                <div class="flex flex-row py-3 items-center">
                    <div class="mr-2">
                        <img
                            :src="getProfilePhoto()"
                            alt="profile photo"
                            class="w-10 h-10 rounded-full object-cover border border-gray-300"
                        >
                    </div>
                    <div>
                        <div
                            class="font-bold md:text-gray-800 text-gray-200 md:text-base text-sm"
                        >
                            {{ publication.data.user.name }}
                        </div>
                        <div
                            class="md:text-sm text-xs font-semibold md:text-gray-500 text-gray-400"
                        >
                            {{ getDateTime(publication.data.created_at) }}
                        </div>
                    </div>
                </div>
                <div class="space-y-6 md:text-gray-800 text-gray-300 md:mb-0 mb-3 max-h-52 md:max-h-96 overflow-y-auto">
                    <div class="md:text-base text-sm">
                        <div v-for="description in descriptionArray" class="leading-tight">
                            {{ description.length ? description : '&nbsp;' }}
                        </div>
                    </div>

                    <div v-show="!isSpecsEmpty()" class="flex flex-col">
                        <ul class="space-y-1 md:text-sm text-xs">
                            <li v-for="specs in specsNames()" :class="{ hidden : publication.data.specifications[specs] === null }">
                            <span class="font-medium uppercase mr-2">
                                {{ specs }}:
                            </span>
                                <span>
                                {{ publication.data.specifications[specs] }}
                            </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="md:mt-6 mt-0">
                <div class="border-t md:border-gray-300 border-gray-100"></div>
                <publication-card-footer :publication="publication.data" :in-show-component="true" />
                <div class="border-t md:border-gray-300 border-gray-100"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    import PublicationCardFooter from "@/Components/PublicationCardFooter";
    import PublicationCard from "@/Components/PublicationCard";

    export default {
        name: "ShowPhoto",
        components: {
            PublicationCard,
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
                return this.publication.data.description.split('\n')
            },
        },
        data () {
            return {
                is_liked: false,
                showDescription: true,
            }
        },
        mounted () {
            let vm = this
            window.onpopstate = function () {
                vm.goBack()
            }
        },
        methods: {
            ...mapActions([
                'setReturnButtonClicked',
                'setScrollPublications'
            ]),

            getProfilePhoto () {
                if (this.publication.data.user.profile_photo_path) {
                    return this.ftpUrl + this.publication.data.user.profile_photo_path
                } else {
                    return this.publication.data.user.profile_photo_url
                }
            },

            getDateTime (dateTime) {
                return moment(dateTime).fromNow();
            },

            goBack() {
                let promise = new Promise(function(myResolve, myReject) {
                    myResolve();
                });

                promise
                    .then(() => {
                        this.setScrollPublications(true)
                    })
                    .then(() => {
                        this.$inertia.get(this.route('publications'))
                    })
            },

            specsNames () {
                return Object.keys(this.publication.data.specifications)
            },

            isSpecsEmpty () {
                let isEmpty = true
                let specs

                for (specs of this.specsNames()) {
                    if (this.publication.data.specifications[specs] !== null) {
                        isEmpty = false
                        break
                    }
                }

                return isEmpty
            },
        }
    }
</script>

<style scoped>

</style>
