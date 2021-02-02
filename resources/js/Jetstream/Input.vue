<template>
    <input class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" :value="value" @input="$emit('input', $event.target.value)" ref="input" :placeholder="placeholder">
</template>

<script>
    export default {
        props: [
            'value',
            'placeholder'
        ],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

