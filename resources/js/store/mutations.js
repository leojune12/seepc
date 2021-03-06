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

    setPublicationShowMutation (state, payload) {
        state.publicationShow = payload
    },

    setLoginMessageMutation (state, payload) {
        state.loginMessage = payload
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

            if (payload.currentUserId && payload.data.current_user_id === payload.currentUserId) {
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
            currentPublication.comments.push(...payload.comments)
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

    setPublicationCommentRepliesMutation (state, payload) {

        let currentPublication = null
        let currentComment = null

        if (state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload.publication_id)

            currentPublication = state.publications[index]
        } else {
            currentPublication = state.publicationShow
        }

        // check if publication is already loaded
        if (currentPublication !== undefined) {

            const index = currentPublication.comments.findIndex(comment =>comment.id === payload.comment_id)

            currentComment = currentPublication.comments[index]

            if (currentComment !== undefined) {
                currentComment.replies.push(...payload.replies)
            }
        }
    },

    addPublicationCommentReplyMutation (state, payload) {

        let currentPublication = null
        let currentComment = null

        if (state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload.data.publication_id)

            currentPublication = state.publications[index]
        } else {
            currentPublication = state.publicationShow
        }

        // check if publication is already loaded
        if (currentPublication !== undefined) {

            const index = currentPublication.comments.findIndex(comment =>comment.id === payload.data.comment_id)

            currentComment = currentPublication.comments[index]

            if (currentComment !== undefined) {
                currentComment.replies.unshift(payload.data.reply)
                currentComment.replies_count = payload.data.replies_count
            }
        }
    },

    updateUserAuthorizationMutation (state, payload) {
        state.userAuthorization = payload
    },

    updateReloadAllPublicationsMutations (state, payload) {
        state.reloadAllPublications = payload
    },

    updateReloadMyPublicationsMutations (state, payload) {
        state.reloadMyPublications = payload
    },

    deletePublicationMutation (state, payload) {
        if (!!state.publications.length) {
            const index = state.publications.findIndex(publication => publication.id === payload)

            if (index > -1) {
                state.publications.splice(index, 1)
            }
        }
    }

}

export default mutations
