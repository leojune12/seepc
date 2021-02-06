<template>
    <app-layout>
        <div class="flex justify-center tracking-tight">
            <div
                class="md:space-y-6 space-y-4 card-container w-full pt-0 pb-6 md:pt-6"
                :class="{ 'pt-4' : !$page.props.user }"
                style="max-width: 31rem;"
            >
                <publish-button v-if="$page.props.user" />

                <publication-card
                    v-for="publication in publications.data"
                    :publication="publication"
                    :key="publication.id"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import PublicationCard from "@/Components/PublicationCard";
    import PublishButton from "@/Components/PublishButton";

    export default {
        name: "Publications",
        components: {
            AppLayout,
            PublicationCard,
            PublishButton
        },
        props: [
            'publications'
        ],
    }
</script>

<style scoped>
    @media (min-width: 640px) {
        .card-container {
            width: 31rem !important;
        }
    }
</style>
