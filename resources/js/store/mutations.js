let mutations = {
    setLastShowedPublicationIdMutation (state, payload) {
        state.lastShowedPublicationId = payload
    },

    setPublicationsMutation (state, payload) {
        state.publications = payload
    },

    setScrollPublicationsMutation (state, payload) {
        state.scrollPublications = payload
    },

    setpublicationsPageMutation (state, payload) {
        state.publicationsPage = payload
    },
}

export default mutations
