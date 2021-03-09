<template>
    <div class="w-full relative">
        <div class="absolute top-0 left-0 w-full z-20">
            <div class="flex justify-center relative">
                <div class="absolute top-0 left-0 w-full flex justify-center">
                    <img
                        :src="[ user.profile_photo_path ? 'https://webdevstacks.000webhostapp.com/'+user.profile_photo_path : user.profile_photo_url ]"
                        :alt="user.name"
                        class="rounded-full h-48 w-48 object-cover border-4 border-white shadow"
                    >
                </div>
            </div>
        </div>
        <div class="absolute top-28 right-3 z-40">
            <jet-dropdown>
                <template #trigger>
                    <button
                        type="button"
                        class="rounded-full hover:bg-gray-100 md:cursor-pointer cursor-default text-gray-500 p-2 focus:outline-none"
                    >
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z" />
                        </svg>
                    </button>
                </template>
                <template #content>
                    <button
                        class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out md:cursor-pointer cursor-default flex justify-start items-center"
                        @click="confirmUpdateProfileInformation"
                    >
                        <span class="mr-2 text-gray-500">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" />
                            </svg>
                        </span>
                        <span>Update Profile</span>
                    </button>
                    <button
                        v-if="!!user.profile_photo_path"
                        class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out md:cursor-pointer cursor-default flex justify-start items-center"
                        @click="confirmPhotoRemoving"
                    >
                        <span class="mr-2 text-gray-500">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" />
                            </svg>
                        </span>
                        <span>Delete Photo</span>
                    </button>
                </template>
            </jet-dropdown>
        </div>
        <div class="h-24"></div>
        <div class="pt-24 w-full bg-white z-10 px-3 md:px-4 pb-3 md:pb-4 text-gray-800 rounded-none md:rounded-xl shadow relative">
            <div class="py-3 space-y-3">
                <div>
                    <div class="text-center text-xl md:text-3xl font-bold">
                        {{ user.name }}
                    </div>
                    <div class="text-center font-semibold text-gray-600">
                        {{ user.email }}
                    </div>
                </div>
                <div>
                    <label class="text-lg font-bold" for="about">
                        About
                    </label>
                    <div class="flex pt-3">
                        <div class="mr-2 text-gray-600">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                            </svg>
                        </div>
                        <p id="about">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec fringilla molestie convallis. Cras quis porta ligula, at egestas nisi. Sed molestie, augue non egestas congue, velit magna molestie eros, sit amet tristique ante urna in orci. Aliquam sed commodo massa, quis fringilla massa. Vestibulum maximus ultrices venenatis. Integer interdum facilisis sodales. Cras maximus tortor eget porta lobortis. Morbi lobortis convallis felis, et eleifend nulla.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Profile Information Modal -->
        <dialog-modal
            max-width="lg"
            :show="updatingProfileInformation"
            @close="closeUpdateProfileInformationModal"
        >
            <template #title>
                Update Profile Information
            </template>
            <template #content>
                <div class="bg-white">
                    <!-- Photo -->
                    <div class="flex justify-center relative">
                        <div class="relative">
                            <div class="animate-pulse ">
                                <div class="h-40 w-40 rounded-full bg-gray-300"></div>
                            </div>
                            <!-- Camera Button -->
                            <div
                                class="absolute bottom-3 right-3 text-gray-700 z-30"
                                @click="selectNewPhoto"
                            >
                                <div class="p-1 rounded-full bg-gray-100 border hover:bg-gray-200 cursor-default md:cursor-pointer">
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Photo File Input -->
                        <input type="file" class="hidden"
                               ref="photo"
                               @change="updatePhotoPreview">

                        <div class="absolute top-0 left-0 w-full flex justify-center">
                            <!-- Current Profile Photo -->
                            <img
                                v-show="!photoPreview"
                                :src="[ user.profile_photo_path ? 'https://webdevstacks.000webhostapp.com/'+user.profile_photo_path : user.profile_photo_url ]"
                                :alt="user.name"
                                class="rounded-full h-40 w-40 object-cover border-4 border-white shadow"
                            >
                            <!-- New Profile Photo Preview -->
                            <span
                                v-show="photoPreview"
                                class="block rounded-full w-40 h-40 border-4 border-white shadow"
                                :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"
                            >
                            </span>
                        </div>
                    </div>

                    <jet-input-error :message="updateForm.errors.photo" class="mt-2" />

                    <div class="pt-3">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <jet-label for="name" value="Name" />
                            <jet-input
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="updateForm.name"
                                autocomplete="name"
                                :disabled="updateForm.processing"
                            />
                            <jet-input-error :message="updateForm.errors.name" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="col-span-6 sm:col-span-4 mt-2">
                            <jet-label for="email" value="Email" />
                            <jet-input
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="updateForm.email"
                                :disabled="updateForm.processing"
                            />
                            <jet-input-error :message="updateForm.errors.email" class="mt-2" />
                        </div>

                    </div>
                </div>
            </template>
            <template #footer>
                <div class="sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        :class="{ 'opacity-25': updateForm.processing }"
                        :disabled="updateForm.processing"
                        @click="updateProfileInformation"
                    >
                        Update
                    </button>
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        :class="{ 'opacity-25': updateForm.processing }"
                        :disabled="updateForm.processing"
                        @click="closeUpdateProfileInformationModal"
                    >
                        Cancel
                    </button>
                </div>
            </template>
        </dialog-modal>

        <!-- Remove Photo Confirmation Modal -->
        <dialog-modal
            max-width="lg"
            :show="confirmingRemovePhoto"
            @close="closeRemoveModal"
        >
            <template #content>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Heroicon name: outline/exclamation -->
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                Remove profile photo
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to remove this profile photo? It will be deleted permanently. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <div class="sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        @click="deletePhoto"
                    >
                        Delete
                    </button>
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="closeRemoveModal"
                    >
                        Cancel
                    </button>
                </div>
            </template>
        </dialog-modal>
    </div>
</template>

<script>
    import JetDropdown from "@/Jetstream/Dropdown"
    import DialogModal from "@/Jetstream/DialogModal"
    import Button from "@/Jetstream/Button"
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        name: "ProfileCard",

        components: {
            Button,
            JetDropdown,
            DialogModal,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: {
            user: {
                type: Object
            }
        },

        computed: {
            showCameraButton () {
                if (!!this.$page.props.user) {
                    return this.$page.props.user.id === this.user.id
                } else {
                    return false
                }
            }
        },

        data() {
            return {
                updateForm: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    email: this.user.email,
                    photo: null,
                }),

                photoPreview: null,

                confirmingRemovePhoto: false,

                updatingProfileInformation: false
            }
        },

        methods: {
            updateProfileInformation() {
                /*if (this.$refs.photo) {
                    this.updateForm.photo = this.$refs.photo.files[0]
                }*/
                this.updateForm.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.closeUpdateProfileInformationModal()
                        this.$inertia.get(route('my-profile'))
                    }
                });
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

                this.updateForm.photo = this.$refs.photo.files[0]
            },

            deletePhoto() {
                this.$inertia.delete(route('user.profile.delete-profile-photo'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.photoPreview = null
                        this.closeRemoveModal()
                    },
                });
            },

            confirmUpdateProfileInformation () {
                this.updatingProfileInformation = true
            },

            confirmPhotoRemoving () {
                this.confirmingRemovePhoto = true;
            },

            closeUpdateProfileInformationModal () {
                new Promise((resolve) => {
                    this.updatingProfileInformation = false

                    setTimeout(() => {
                        resolve()
                    }, 500)
                }).then(() => {
                    // Reset Form
                    this.updateForm.reset()
                    this.photoPreview = null
                })
            },

            closeRemoveModal () {
                this.confirmingRemovePhoto = false
            },
        }
    }
</script>

<style scoped>

</style>
