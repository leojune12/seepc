let actions = {
    setLastShowedPublicationId ({commit}, payload) {
        commit('setLastShowedPublicationIdMutation', payload)
    },

    setPublications ({commit}, payload) {
        commit('setPublicationsMutation', payload)
    },

    setScrollPublications ({commit}, payload) {
        commit('setScrollPublicationsMutation', payload)
    },

    setPublicationsPage ({commit}, payload) {
        commit('setpublicationsPageMutation', payload)
    },
}

export default actions
