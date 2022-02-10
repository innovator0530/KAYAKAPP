import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    getCategoryModalityWithPart(context, competitionId) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/admin/competition/category-modality-participant/"  + competitionId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.GET_CATEGORY_MODALITY_WITH_PART, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getParticipantsByCompetitionCategoryModality(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/competition/category-modality/participants", data)
                .then(({data}) => {
                     console.log(data);
                    context.commit(type.SET_PARTICIPANTS_COMPETITION_CATEGORY_MODALITY, data)
                    resolve(data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    unregistParticipantToCompetitionCategoryModality(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/competition/category-modality/participant/unregist", data)
                .then((res) => {
                    toastr.success('Participante eliminado Correctamenteamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                    resolve(res)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    createFirstCompetitionBoxes(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-box/create", data)
                .then((data) => {
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    deleteCompetitionBoxes(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-box/delete", data)
                .then((data) => {
                    toastr.success('Eliminada Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    initCompetitionHeats(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-heats", data)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.GET_ALL_ROUND_HEATS, data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    getCompetitionFinalResults(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-heats/final-results", data)
                .then((data) => {
                    // console.log(data);
                    resolve(data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    setProgressStatus(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-heat/progress-status", data)
                .then((data) => {
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    initHeatDetails(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-heat/heat-details", data)
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.GET_ROUND_HEAT_DETAILS, data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
    storeFinalHeatResults(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/live-management/competition-heat/heat-details/store", data)
                .then((data) => {
                    toastr.success('Guardado Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },

    initHome(context) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/home")
                .then(({data}) => {
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },

    initHomeHeatDetails(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/competition-heat/heat-details", data)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.GET_ROUND_HEAT_DETAILS, data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },

    getCompetitionHeats(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/home/competition-heats", data)
                .then(({data}) => {
                    resolve(data);
                })
                .catch(({ response }) => {
                    // console.log(response);
                    reject(response)
                });
        });
    },
};

export default actions;
