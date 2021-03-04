let state = {
    ftpUrl: 'https://webdevstacks.000webhostapp.com/',
    lastShowedPublicationId: null,
    publications: [],
    publicationShow: null,
    scrollPublications: false,
    publicationsPage: 1,
    loginMessage: null,

    userAuthorization: {
        firstVisit: true,
        status: false
    }
}

export default state
