<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-3 md:py-6 px-0 lg:px-8">
            <jet-form-section
                @submitted="submitForm"
            >
                <template #title>
                    {{ !!publication ? 'Update' : 'Publish' }} your PC
                </template>

                <template #description>
                    {{ !!publication ? 'Update' : 'Publish' }} photo and specifications of your PC
                </template>

                <template #form>

                    <!-- Description -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="description" value="DESCRIPTION" />
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            class="border border-gray-300 focus:border-gray-400 rounded-xl focus:ring-0 shadow-sm mt-1 w-full resize-none"
                            v-model="form.description"
                            required
                            placeholder="Say something about your PC..."
                            :disabled="form.processing"
                        ></textarea>
                        <jet-input-error :message="form.errors.description" class="mt-2" />
                    </div>

                    <!-- Photo -->
                    <div class="col-span-6 sm:col-span-4">
                        <!-- Photo File Input -->
                        <input
                            id="photo"
                            type="file"
                            class="hidden"
                            ref="photo"
                            @change="updatePhotoPreview"
                        >

                        <!-- New Photo Preview -->
                        <div
                            class="mb-2"
                            v-show="photoPreview"
                        >
                            <jet-label for="photo" value="PHOTO" />
                            <img
                                :src="photoPreview"
                                alt="photo"
                                class="object-contain w-full h-auto border bg-gray-900 rounded-xl shadow-sm mt-1 max-h-96"
                            >
                        </div>

                        <!-- Current Photo -->
                        <div
                            class="mb-2"
                            v-if="!photoPreview && !!form.currentPhotoPath"
                        >
                            <img
                                :src="ftpUrl+form.currentPhotoPath"
                                alt="photo"
                                class="object-contain w-full h-auto border bg-gray-900 rounded-xl shadow-sm mt-1 max-h-96"
                            >
                        </div>

                        <!-- New Photo Button -->
                        <button
                            type="button"
                            class="mt-1 rounded-full border border-gray-300 h-10 md:cursor-pointer flex items-center justify-center hover:bg-gray-100 md:w-52 shadow-sm text-gray-800 md:cursor-pointer cursor-default focus:outline-none md:w-auto w-full px-5"
                            :disabled="form.processing"
                            @click="selectNewPhoto"
                        >
                            <span class="text-gray-500 mr-1">
                                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z" />
                                </svg>
                            </span>
                            <span>Select a photo</span>
                        </button>

                        <jet-input-error :message="form.errors.photo" class="mt-2" />

                        <jet-input-error :message="photoNullError" class="mt-2" />
                    </div>

                    <!-- Specifications Selection -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="specs" value="SELECT SPECS TO INCLUDE" />
                        <div
                            id="specs"
                            class="flex flex-wrap mt-2"
                        >
                            <button
                                type="button"
                                v-for="(value, name) in specifications"
                                :class="[ value ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-blue-700 hover:bg-blue-100' ]"
                                class="flex px-3 rounded-full mr-1 mb-1 md:cursor-pointer cursor-default border border-blue-600 h-7 items-center shadow-sm focus:outline-none"
                                :disabled="form.processing"
                                @click="toggleSpecs(name)"
                            >
                                <span class="text-xs font-medium uppercase">
                                    {{ name }}
                                </span>
                            </button>
                        </div>

                        <!-- Specifications selected -->
                        <div class="">
                            <transition-group
                                enter-active-class="transition ease-in duration-300"
                                enter-class="opacity-0"
                                enter-to-class="opacity-100"
                                leave-active-class="transition ease-in duration-300"
                                leave-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <div
                                    v-for="(value, name) in specifications"
                                    :key="name+'-key'"
                                    v-show="value"
                                    class="mb-2 mt-3"
                                >
                                    <label :for="name" class="uppercase font-medium text-gray-600 text-xs">
                                        {{ name }}
                                    </label>
                                    <div
                                        class="flex items-center justify-center px-3 border border-gray-300 mt-1 focus:border-gray-400 rounded-xl shadow-sm"
                                    >
                                        <input
                                            type="text"
                                            class="flex-1 border-none focus:border-none focus:ring-0 pl-0 pr-3"
                                            v-model="form[name]"
                                            :disabled="form.processing"
                                        />
                                        <!-- Close button -->
                                        <button
                                            type="button"
                                            class="flex-none md:cursor-pointer cursor-default rounded-full hover:bg-gray-100 w-7 h-7 text-gray-600 flex items-center justify-center focus:outline-none"
                                            :disabled="form.processing"
                                            @click="toggleSpecs(name)"
                                        >
                                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <jet-input-error :message="form.errors[name]" class="mt-2" />
                                </div>
                            </transition-group>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <div class="sm:flex sm:flex-row-reverse w-full">
                        <!--<jet-action-message :on="form.recentlySuccessful" class="mr-3">
                            Published.
                        </jet-action-message>-->

                        <jet-action-message :on="form.processing" class="mr-3 hidden md:block">
                            {{ !!publication ? 'Updating...' : 'Publishing...' }}
                        </jet-action-message>

                        <jet-button
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 w-full md:w-auto flex items-center justify-center sm:block block-inline"
                        >
                            {{ !!publication ? 'Update' : 'Publish' }}
                        </jet-button>

                        <inertia-link
                            v-show="!!publication"
                            :href="route('my-profile')"
                            class="flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-100 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 sm:mr-3 mt-3 sm:mt-0 text-gray-700 border border-gray-300 shadow-sm sm:block block-inline justify-center"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Cancel
                        </inertia-link>
                    </div>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import { mapActions } from 'vuex'

    export default {
        name: "Create",

        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: {
            publication: {
                type: Object,
                default: null
            }
        },

        computed: {
            ftpUrl () {
                return this.$store.state.ftpUrl
            },

            form () {
                if (!!this.publication) {
                    return this.$inertia.form({
                        currentPhotoPath: this.publication.data.photo_path,
                        photo: null,
                        description: this.publication.data.description,
                        motherboard: this.publication.data.specifications.motherboard,
                        cpu: this.publication.data.specifications.cpu,
                        ram: this.publication.data.specifications.ram,
                        graphics: this.publication.data.specifications.graphics,
                        storage: this.publication.data.specifications.storage,
                        display: this.publication.data.specifications.display,
                        keyboard: this.publication.data.specifications.keyboard,
                        mouse: this.publication.data.specifications.mouse,
                    })
                } else {
                    return this.$inertia.form({
                        photo: null,
                        description: null,
                        motherboard: null,
                        cpu: null,
                        ram: null,
                        graphics: null,
                        storage: null,
                        display: null,
                        keyboard: null,
                        mouse: null,
                    })
                }
            },

            specificationValues () {
                if (!!this.publication) {
                    return {
                        // motherboard: !!this.publication ? !!this.publication.data.specifications.motherboard : false,
                        motherboard: !!this.publication.data.specifications.motherboard,
                        cpu: !!this.publication.data.specifications.cpu,
                        ram: !!this.publication.data.specifications.ram,
                        graphics: !!this.publication.data.specifications.graphics,
                        storage: !!this.publication.data.specifications.storage,
                        display: !!this.publication.data.specifications.display,
                        keyboard: !!this.publication.data.specifications.keyboard,
                        mouse: !!this.publication.data.specifications.mouse,
                    }
                } else {
                    return {
                        motherboard: false,
                        cpu: false,
                        ram: false,
                        graphics: false,
                        storage: false,
                        display: false,
                        keyboard: false,
                        mouse: false,
                    }
                }
            }
        },

        data() {
            return {
                photoPreview: null,
                photoNullError: null,
                specifications: null,
            }
        },

        mounted () {
            this.specifications = this.specificationValues
        },

        methods: {
            ...mapActions([
                'setPublications',
            ]),

            submitForm () {
                if (!!this.publication) {
                    this.updatePublication()
                } else {
                    this.publish()
                }
            },

            publish() {
                // empty publications to update publications in Publications page
                this.setPublications([])

                if (this.photoPreview !== null) {
                    this.photoNullError = null

                    if (this.$refs.photo) {
                        this.form.photo = this.$refs.photo.files[0]
                    }

                    this.$inertia.post(route('publications.store'), this.form);
                } else {
                    this.photoNullError = 'Photo is required'
                }
            },

            updatePublication () {
                this.setPublications([])

                if (this.photoPreview !== null) {
                    if (this.$refs.photo) {
                        this.form.photo = this.$refs.photo.files[0]
                    }
                }

                this.form.publication_id = this.publication.data.id

                this.$inertia.post(route('publications.update'), this.form);
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                if (this.$refs.photo.files[0]) {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.photoPreview = e.target.result;
                    };

                    reader.readAsDataURL(this.$refs.photo.files[0]);

                    this.photoNullError = null
                } else {
                    this.photoPreview = null
                }
            },

            toggleSpecs(name) {
                this.specifications[name] = !this.specifications[name]
                this.form[name] = null
            }
        }
    }
</script>
