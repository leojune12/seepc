<template>
    <div class="flex flex-col md:flex-row min-h-screen tracking-tight">
        <div class="md:flex-grow bg-black">
            <span
                class="absolute top-3 md:top-8 left-3 md:left-8 rounded-full bg-gray-200 hover:bg-gray-300 md:h-10 md:w-10 w-8 h-8 flex justify-center items-center md:cursor-pointer"
                @click="goBack"
            >
                <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                </svg>
            </span>
            <img :src="ftpUrl+publication.data.photo_path" alt="photo" class="w-full h-screen object-contain" @click="showDescription = !showDescription">
        </div>
        <div :class="{ 'hidden md:block' : !showDescription }" class="w-full md:w-96 md:flex-none md:relative absolute bottom-0 bg-opacity-50 md:bg-opacity-100 bg-black md:bg-white">
            <div class="">
                <div class="flex flex-row px-4 py-3 items-center">
                    <div class="mr-2">
                        <img
                            :src="getProfilePhoto()"
                            alt="profile photo"
                            class="w-10 h-10 rounded-full object-cover border border-gray-300"
                        >
                    </div>
                    <div>
                        <div
                            class="font-bold md:text-gray-800 text-white md:text-base text-sm"
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
                <div class="px-3 md:px-4 space-y-6 md:text-gray-800 text-white md:mb-0 mb-3 overflow-y-auto max-h-52 md:max-h-96 overflow-y-auto">
                    <div class="md:text-base text-sm">
                        <div v-for="description in descriptionArray" class="leading-tight">
                            {{ description.length ? description : '&nbsp;' }}
                        </div>
                    </div>

                    <div v-show="!isSpecsEmpty()" class="flex flex-col">
                        <ul class="space-y-1 md:text-sm text-xs">
                            <li v-for="specs in specsNames()" :class="{ hidden : publication.data.specifications[specs] === null }">
                            <span class="font-bold uppercase mr-2">
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
            <div class="px-3 md:px-4 md:mt-6 mt-0">
                <div class="border-t md:border-gray-300 border-gray-100"></div>
                <div class="py-1 flex">
                    <button
                        class="h-9 md:text-gray-500 text-gray-100 rounded focus:outline-none flex-1 text-center hover:bg-gray-100 active:bg-gray-300"
                    >
                        <div class="flex justify-center">
                            <div class="flex text-gray-500">
                                <div class="mr-2">
                                    <svg v-if="is_liked" style="width:20px;height:20px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M23,10C23,8.89 22.1,8 21,8H14.68L15.64,3.43C15.66,3.33 15.67,3.22 15.67,3.11C15.67,2.7 15.5,2.32 15.23,2.05L14.17,1L7.59,7.58C7.22,7.95 7,8.45 7,9V19A2,2 0 0,0 9,21H18C18.83,21 19.54,20.5 19.84,19.78L22.86,12.73C22.95,12.5 23,12.26 23,12V10M1,21H5V9H1V21Z" />
                                    </svg>
                                    <svg v-else style="width:20px;height:20px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M5,9V21H1V9H5M9,21A2,2 0 0,1 7,19V9C7,8.45 7.22,7.95 7.59,7.59L14.17,1L15.23,2.06C15.5,2.33 15.67,2.7 15.67,3.11L15.64,3.43L14.69,8H21C22.11,8 23,8.9 23,10V12C23,12.26 22.95,12.5 22.86,12.73L19.84,19.78C19.54,20.5 18.83,21 18,21H9M9,19H18.03L21,12V10H12.21L13.34,4.68L9,9.03V19Z" />
                                    </svg>
                                </div>
                                <span class="font-bold" style="font-size: 15px;">Like</span>
                            </div>
                        </div>
                    </button>
                    <button class="h-9 text-gray-500 rounded hover:bg-gray-100 hover:text-indigo-100 focus:outline-none flex-1 text-center">
                        <div class="flex justify-center">
                            <div class="flex text-gray-500">
                                <div class="mr-2">
                                    <svg style="width:20px;height:20px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M9,22A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9M10,16V19.08L13.08,16H20V4H4V16H10Z" />
                                    </svg>
                                </div>
                                <span class="font-bold" style="font-size: 15px;">Comment</span>
                            </div>
                        </div>
                    </button>
                </div>
                <div class="border-t md:border-gray-300 border-gray-100"></div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "ShowPhoto",
        props: [
            'publication'
        ],
        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            descriptionArray () {
                return this.publication.data.description.split('\n')
            }
        },
        data () {
            return {
                is_liked: false,
                showDescription: true
            }
        },
        methods: {
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
                if (window.history.length > 2) {
                    window.history.back()
                } else {
                    this.$inertia.get(this.route('publications'))
                }
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
            }
        }
    }
</script>

<style scoped>

</style>
