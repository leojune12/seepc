<template>
    <div
        class="pb-3"
        :class="[ inShowComponent ? 'md:text-gray-800 text-gray-200' : 'text-gray-800' ]"
    >
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
                    class="font-bold md:text-base text-sm"
                >
                    {{ publication.user.name }}
                </div>
                <div
                    class="md:text-sm text-xs font-semibold md:text-gray-500 text-gray-400"
                >
                    {{ getDateTime(publication.created_at) }}
                </div>
            </div>
        </div>
        <div
            class="space-y-6 overflow-y-auto"
            :class="[ inShowComponent ? 'max-h-48 md:max-h-96' : '' ]"
        >
            <div class="md:text-base text-sm">
                <div v-for="description in descriptionArray">
                    {{ description.length ? description : '&nbsp;' }}
                </div>
            </div>

            <div v-show="!isSpecsEmpty()" class="flex flex-col">
                <ul class="space-y-1 md:text-base text-sm">
                    <li v-for="specs in specsNames()" :class="{ hidden : publication.specifications[specs] === null }">
                            <span class="md:font-medium font-bold uppercase mr-2">
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
</template>

<script>
    export default {
        name: "PublicationDescriptions",
        props: {
            publication: {
                type: Object
            },
            inShowComponent: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            descriptionArray () {
                return this.publication.description.split('\n')
            },
        },
        methods: {
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
            },
        }
    }
</script>

<style scoped>

</style>
