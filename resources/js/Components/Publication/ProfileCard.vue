<template>
    <div class="w-full relative">
        <div class="absolute top-0 left-0 w-full z-20">
            <div class="flex justify-center relative">
                <div class="animate-pulse">
                    <div class="h-48 w-48 rounded-full bg-gray-300"></div>
                </div>
                <div class="absolute top-0 left-0 w-full flex justify-center">
                    <img :src="[ user.profile_photo_path ? 'https://webdevstacks.000webhostapp.com/'+user.profile_photo_path : user.profile_photo_url ]" :alt="user.name" class="rounded-full h-48 w-48 object-cover border-4 border-white shadow">
                </div>
            </div>
        </div>
        <div class="h-24"></div>
        <div class="pt-24 w-full bg-white z-10 px-3 md:px-4 pb-3 md:pb-4 text-gray-800 rounded-none md:rounded-xl">
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
    </div>
</template>

<script>
    export default {
        name: "ProfileCard",

        props: {
            user: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
