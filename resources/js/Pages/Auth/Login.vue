<template>
    <!--<jet-authentication-card>
        &lt;!&ndash;<template #logo>
            <jet-authentication-card-logo />
        </template>&ndash;&gt;

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Login
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>-->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <inertia-link href="/">
                    <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
                </inertia-link>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sign in to your account
                </h2>
            </div>

            <jet-validation-errors class="mb-4" />

            <info-message
                v-if="loginMessage"
                :message="loginMessage"
            />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm space-y-3">
                    <div>
                        <jet-label for="email" value="Email" />
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            v-model="form.email"
                            autofocus
                        >
                    </div>
                    <div>
                        <jet-label for="password" value="Password" />
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            v-model="form.password"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="flex items-center">
                            <jet-checkbox name="remember" v-model="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="text-sm">
                        <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                            Forgot your password?
                        </inertia-link>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :class="{ 'opacity-25 cursor-default': form.processing }"
                        :disabled="form.processing"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Heroicon name: lock-closed -->
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>
                <div>
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-gray-100 text-gray-500">Don't have an account?</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <inertia-link
                            :href="route('register')"
                            class="block w-full text-center py-2 px-3 border border-gray-300 rounded-md hover:border-gray-400 focus:outline-none focus:border-gray-400 sm:text-sm"
                        >
                            <span class="text-gray-900 font-bold">
                                Register
                            </span>
                        </inertia-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import { mapActions } from 'vuex'
    import InfoMessage from "@/Components/InfoMessage"

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            InfoMessage
        },

        props: {
            canResetPassword: Boolean,
            status: String,
        },

        computed: {
            loginMessage () {
                return this.$store.state.loginMessage
            }
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            ...mapActions([
                'setLoginMessage'
            ]),

            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        },

        beforeDestroy() {
            if (this.loginMessage) {
                this.setLoginMessage(null)
            }
        }
    }
</script>
