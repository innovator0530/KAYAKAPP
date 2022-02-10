import store from '../store'

export default [
    {
        path: '/',
        meta: {
        },
        name: 'Home',
        component: () => import('../contains/dashboard/home'),
    },
    {
        path: '/competition/:competitionId/category/:categoryId/modality/:modalityId/round/:round/heat/:heat',
        meta: {
        },
        name: 'Details',
        component: () => import('../contains/dashboard/details'),
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../contains/auth/login'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                // If the user is already logged in
                if (store.getters['isAuthenticated']) {
                    // Redirect to the home page instead
                    if (store.getters['currentRole'] == 'Admin') {
                        next({ name: 'Users' })
                    } else if (store.getters['currentRole'] == 'Judge') {
                        next({ name: 'Judge' })
                    } else {
                        next({ name: 'Home' })
                    }
                } else {
                    // Continue to the login page
                    next()
                }
            },
        },
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('../contains/auth/register'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                // If the user is already logged in
                if (store.getters['isAuthenticated']) {
                    // Redirect to the home page instead
                    if (store.getters['currentRole'] == 'Admin') {
                        next({ name: 'Users' })
                    } else if (store.getters['currentRole'] == 'Judge') {
                        next({ name: 'Judge' })
                    } else {
                        next({ name: 'Home' })
                    }
                } else {
                    // Continue to the login page
                    next()
                }
            },
        },
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('../contains/auth/forgot-password'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                // If the user is already logged in
                if (store.getters['isAuthenticated']) {
                    // Redirect to the home page instead
                    if (store.getters['currentRole'] == 'Admin') {
                        next({ name: 'Users' })
                    } else if (store.getters['currentRole'] == 'Judge') {
                        next({ name: 'Judge' })
                    } else {
                        next({ name: 'Home' })
                    }
                } else {
                    // Continue to the login page
                    next()
                }
            },
        },  
    },
    {
        path: '/logout',
        name: 'logout',
        meta: {
            authRequired: true,
            beforeResolve(routeTo, routeFrom, next) {
                store.dispatch('logout')
                const authRequiredOnPreviousRoute = routeFrom.matched.some(
                    (route) => route.push('/login')
                )
                // Navigate back to previous page, or home as a fallback
                next(authRequiredOnPreviousRoute ? { name: 'Users' } : { ...routeFrom })
            },
        },
    },

    {
        path: '/judge',
        name: 'Judge',
        component: () => import('../contains/judge'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Judge') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/users',
        name: 'Users',
        component: () => import('../contains/admin/user/users'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/user/create',
        name: 'UserCreate',
        component: () => import('../contains/admin/user/user-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/user/edit/:userId',
        name: 'UserEdit',
        component: () => import('../contains/admin/user/user-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/categories',
        name: 'Categories',
        component: () => import('../contains/admin/category/categories'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/category/create',
        name: 'CategoryCreate',
        component: () => import('../contains/admin/category/category-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/category/edit/:categoryId',
        name: 'CategoryEdit',
        component: () => import('../contains/admin/category/category-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/lycras',
        name: 'Lycras',
        component: () => import('../contains/admin/lycra/lycras'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/lycra/create',
        name: 'LycraCreate',
        component: () => import('../contains/admin/lycra/lycra-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/lycra/edit/:lycraId',
        name: 'LycraEdit',
        component: () => import('../contains/admin/lycra/lycra-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/clubs',
        name: 'Clubs',
        component: () => import('../contains/admin/club/clubs'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/club/create',
        name: 'ClubCreate',
        component: () => import('../contains/admin/club/club-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/club/edit/:clubId',
        name: 'ClubEdit',
        component: () => import('../contains/admin/club/club-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/competition_types',
        name: 'CompetitionTypes',
        component: () => import('../contains/admin/competition_type/competition_types'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition_type/create',
        name: 'CompetitionTypeCreate',
        component: () => import('../contains/admin/competition_type/competition_type-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition_type/edit/:competition_typeId',
        name: 'CompetitionTypeEdit',
        component: () => import('../contains/admin/competition_type/competition_type-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/competitions',
        name: 'Competitions',
        component: () => import('../contains/admin/competition/competitions'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition/create',
        name: 'CompetitionCreate',
        component: () => import('../contains/admin/competition/competition-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition/edit/:competitionId',
        name: 'CompetitionEdit',
        component: () => import('../contains/admin/competition/competition-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition/participant/regist/:competitionId',
        name: 'CompetitionParticipantRegist',
        component: () => import('../contains/admin/competition/competition-participant-regist'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/competition/participant/add/:competitionId',
        name: 'CompetitionParticipantAdd',
        component: () => import('../contains/admin/competition/competition-participant-add'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/live-management/:competitionId/determined-participants',
        name: 'DeterminedParticipants',
        component: () => import('../contains/admin/live-management/determined-participants'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/live-management/competition/:competitionId/category/:categoryId/modality/:modalityId',
        name: 'CompetitionHeats',
        component: () => import('../contains/admin/live-management/competition-heats'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/live-management/:competitionId/category/:categoryId/modality/:modalityId/round/:round/heat/:heat',
        name: 'CompetitionHeatDetails',
        component: () => import('../contains/admin/live-management/heat-details'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/participants',
        name: 'Participants',
        component: () => import('../contains/admin/participant/participants'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/participant/create',
        name: 'ParticipantCreate',
        component: () => import('../contains/admin/participant/participant-create'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/participant/edit/:participantId',
        name: 'ParticipantEdit',
        component: () => import('../contains/admin/participant/participant-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },

    {
        path: '/admin/ranking_points',
        name: 'RankingPoints',
        component: () => import('../contains/admin/ranking_points/ranking_points'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    // {
    //     path: '/admin/ranking_points/create',
    //     name: 'RankingPointsCreate',
    //     component: () => import('../contains/admin/ranking_points/ranking_points-create'),
    //     meta: {
    //         
    //     },
    // },
    {
        path: '/admin/ranking_points/edit/:rankingId',
        name: 'RankingPointsEdit',
        component: () => import('../contains/admin/ranking_points/ranking_points-edit'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
    {
        path: '/admin/category-ranking-menu',
        name: 'CategoryRankingMenu',
        component: () => import('../contains/admin/manage_ranking/category-ranking-menu'),
        meta: {
            beforeResolve(routeTo, routeFrom, next) {
                if (store.getters['currentRole'] == 'Admin') {
                    next()
                } else {
                    next({ name: 'login' })
                }
            },
        },
    },
]