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

    setPublicationsPageMutation (state, payload) {
        state.publicationsPage = payload
    },

    setPublicationLikesMutation (state, payload) {
        let currentPublication = null

        if (state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload.data.publication_id)

            currentPublication = state.publications[index]
        } else {
            currentPublication = state.publicationShow
        }

        // check if publication is already loaded
        if (currentPublication !== undefined) {
            currentPublication.likes_count = payload.data.likes_count

            if (payload.data.current_user_id === payload.currentUserId) {
                currentPublication.liked = payload.data.liked
            }
        }
    },

    setPublicationCommentsMutation (state, payload) {
        let currentPublication = null

        if (state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload.publication_id)

            currentPublication = state.publications[index]
        } else {
            currentPublication = state.publicationShow
        }

        // check if publication is already loaded
        if (currentPublication !== undefined) {
            currentPublication.comments = payload.comments
        }
    },

    addPublicationCommentMutation (state, payload) {
        let currentPublication = null

        if (state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload.data.publication_id)

            currentPublication = state.publications[index]
        } else {
            currentPublication = state.publicationShow
        }

        // check if publication is already loaded
        if (currentPublication !== undefined) {
            currentPublication.comments.unshift(payload.data.comment)
            currentPublication.comments_count = payload.data.comments_count
        }
    },

    setPublicationShowMutation (state, payload) {
        state.publicationShow = payload
    }
}

export default mutations
