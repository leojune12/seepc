let state = {
    // FPT url of media storage
    // ftpUrl: 'https://webdevstacks.000webhostapp.com/',
    ftpUrl: '/storage/',

    // id of the last publication visited
    lastShowedPublicationId: null,

    publicationShow: null,

    // publications currently loaded
    publications: [],

    // flag if to scroll to a certain image in publications
    scrollPublications: false,

    // message for guest user in login page
    loginMessage: null,

    userAuthorization: {
        firstVisit: true,
        status: false
    },

    currentRoute: {
        route: null,
        params: null
    }
}

export default state
