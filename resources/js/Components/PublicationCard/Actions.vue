<template>
    <div>
        <div>
            <jet-dropdown>
                <template #trigger>
                    <span class="rounded-full hover:bg-gray-100 w-9 h-9 flex items-center justify-center text-gray-500 md:cursor-pointer">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z" />
                        </svg>
                    </span>
                </template>
                <template #content>
                    <!-- Actions -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Actions
                    </div>

                    <div
                        class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out md:cursor-pointer"
                    >
                        Edit
                    </div>

                    <div
                        class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out md:cursor-pointer"
                        @click="confirmDeletion"
                    >
                        Delete
                    </div>
                </template>
            </jet-dropdown>
        </div>
        <!-- Delete Account Confirmation Modal -->
        <dialog-modal
            max-width="lg"
            :show="confirmingDeletion"
            @close="closeModal"
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
                                Delete publication
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this publication? All of its data will be permanently removed. This action cannot be undone.
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
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deletePublicationFromStore"
                    >
                        Delete
                    </button>
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="closeModal"
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
    import { mapActions } from 'vuex'

    export default {
        name: "Actions",

        components: {
            JetDropdown,
            DialogModal
        },

        props: {
            publication: {
                type: Object
            },
        },

        data () {
            return {
                confirmingDeletion: false,

                deleteForm: this.$inertia.form({
                    publication_id: this.publication.id
                })
            }
        },

        methods: {
            ...mapActions([
                'deletePublication',
            ]),

            confirmDeletion () {
                this.confirmingDeletion = true;
            },

            closeModal () {
                this.confirmingDeletion = false
            },

            deletePublicationFromStore () {
                this.deleteForm.delete(route('publications.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.closeModal()
                    },
                    onFinish: () => {
                        this.deletePublication(this.publication.id)
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>
