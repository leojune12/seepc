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
}

export default mutations
