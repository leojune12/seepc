<template>
    <app-layout>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form-section @submitted="publish">
                <template #title>
                    Publish your PC setup
                </template>

                <template #description>
                    Post your PC specs and photo
                </template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="specs" value="SELECT SPECS TO INCLUDE" />
                        <div id="specs" class="flex flex-wrap mt-2">
                            <span v-for="specs in addSpecs" :class="[ specs.show ? 'bg-blue-600 text-white' : 'bg-white text-blue-700' ]" class="flex py-1 px-3 rounded-full mr-1 mb-2 cursor-pointer border border-blue-500" type="button" @click="removeSpecs(specs.name)">
                                <!-- Plus -->
                                <svg v-if="!specs.show" style="width:18px;height:18px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                </svg>
                                <!-- Close -->
                                <svg v-else style="width:18px;height:18px" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                </svg>
                                <span class="text-sm font-bold uppercase">
                                    {{ specs.name }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="description" value="DESCRIPTION" />
                        <textarea name="description" id="description" rows="4" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.description" required placeholder="Say something about your PC..."></textarea>
                        <jet-input-error :message="form.errors.description" class="mt-2" />
                    </div>
                    <div v-for="specs in addSpecs" class="col-span-6 sm:col-span-4" v-show="specs.show">
                        <jet-label :for="specs.name" class="uppercase" :value="specs.name" />
                        <jet-input type="text" class="mt-1 block w-full" v-model="form[specs.name]" />
                        <jet-input-error :message="form.errors[specs.name]" class="mt-2" />
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

                        <jet-label for="photo" value="PHOTO" />

                        <!-- New Photo Preview -->
                        <div class="mt-2" v-show="photoPreview">
                            <img :src="photoPreview" alt="photo" class="object-contain w-full h-80">
                        </div>

                        <jet-secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewPhoto">
                            Select A New Photo
                        </jet-secondary-button>

                        <label for="photo" class="text-red-500 block mt-1 text-sm" v-show="photoError">Photo is required</label>

                        <jet-input-error :message="form.errors.photo" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                        Published.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Publish
                    </jet-button>
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
        data() {
            return {
                form: this.$inertia.form({
                    _method: 'POST',
                    user_id: this.$page.props.user.id,
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
                }),

                addSpecs: {
                    motherboard: {
                        show: false,
                        name: 'motherboard'
                    },
                    cpu: {
                        show: false,
                        name: 'cpu'
                    },
                    ram: {
                        show: false,
                        name: 'ram'
                    },
                    graphics: {
                        show: false,
                        name: 'graphics'
                    },
                    storage: {
                        show: false,
                        name: 'storage'
                    },
                    display: {
                        show: false,
                        name: 'display'
                    },
                    keyboard: {
                        show: false,
                        name: 'keyboard'
                    },
                    mouse: {
                        show: false,
                        name: 'mouse'
                    },
                },

                photoPreview: null,
                photoError: false
            }
        },
        methods: {
            publish() {
                if (this.photoPreview !== null) {
                    this.photoError = false

                    if (this.$refs.photo) {
                        this.form.photo = this.$refs.photo.files[0]
                    }

                    this.form.post(route('publication.store'), {
                        preserveScroll: true
                    });
                } else {
                    this.photoError = true
                }
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);

                this.photoError = false
            },

            removeSpecs(specsName) {
                let specs = this.addSpecs[specsName]
                specs.show = !specs.show
                this.form[specsName] = null
            }
        }
    }
</script>

<style scoped>

</style>
