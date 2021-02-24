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

    setPublicationLikes ({commit}, payload) {
        commit('setPublicationLikesMutation', payload)
    },

    setPublicationShow ({commit}, payload) {
        commit('setPublicationShowMutation', payload)
    }
}

export default actions
