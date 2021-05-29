<template>
        <div
            class="bg-white p-4 w-full focus:outline-none flex items-center shadow rounded-none md:rounded-xl"
        >
            <inertia-link
                :href="route('profile.show')"
                class="flex-none mr-2 cursor-default md:cursor-pointer"
            >
                <img class="h-10 w-10 rounded-full object-cover border border-gray-300" :src="[ $page.props.user.profile_photo_path ? '/storage/'+$page.props.user.profile_photo_path : $page.props.user.profile_photo_url ]" :alt="$page.props.user.name" />
            </inertia-link>

            <inertia-link
                :href="route('publications.create')"
                class="border border-blue-100 rounded-full px-4 py-2 hover:bg-gray-100 flex-grow flex text-blue-500 cursor-default md:cursor-pointer"
            >
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" />
                </svg>
                <span class="ml-2 font-bold">
                    Publish your PC setup
                </span>
            </inertia-link>
        </div>
</template>

<script>
    export default {
        name: "PublishButton"
    }
</script>

<style scoped>

</style>
