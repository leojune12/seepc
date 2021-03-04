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
        commit('setPublicationsPageMutation', payload)
    },

    setPublicationLikes ({commit}, payload) {
        commit('setPublicationLikesMutation', payload)
    },

    setPublicationComments ({commit}, payload) {
        commit('setPublicationCommentsMutation', payload)
    },

    addPublicationComment ({commit}, payload) {
        commit('addPublicationCommentMutation', payload)
    },

    setPublicationShow ({commit}, payload) {
        commit('setPublicationShowMutation', payload)
    },

    setLoginMessage ({commit}, payload) {
        commit('setLoginMessageMutation', payload)
    },

    setPublicationCommentReplies ({commit}, payload) {
        commit('setPublicationCommentRepliesMutation', payload)
    },

    addPublicationCommentReply ({commit}, payload) {
        commit('addPublicationCommentReplyMutation', payload)
    },

    updateUserAuthorization ({commit}, payload) {
        commit('updateUserAuthorizationMutation', payload)
    }
}

export default actions
