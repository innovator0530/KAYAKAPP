import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service"
import type from './type'
import router from '../../router/index'
import axios from "axios";

const actions = {
    initCompetitions(context) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/admin/competitions")
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_ALL_COMPETITION, data)
                })
                .catch(({ response }) => {
                    reject(response)
                });
        });
    },
    getCompetitionById(context, competitionId) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/admin/competition/" + competitionId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_COMPETITION, data)
                })
                .catch(({ response }) => {
                    reject(response)
                });
        }); 
    },
    createCompetition(context, competitionInfo) {
        const config = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                "Authorization":`Bearer ${JwtService.getToken()}`,
                'content-type': 'multipart/form-data'
            }
        }
        return new Promise((resolve, reject) => {
            axios.post('http://localhost:8000/api/v1/admin/competition/create', competitionInfo, config)
                .then((res) => {
                    resolve(res)
                    toastr.success('Creada Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    updateCompetition(context, competitionInfo) {
        const config = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                "Authorization":`Bearer ${JwtService.getToken()}`,
                'content-type': 'multipart/form-data'
            }
        }
        return new Promise((resolve, reject) => {
            axios.post('http://localhost:8000/api/v1/admin/competition/update', competitionInfo, config)
                .then((res) => {
                    resolve(res)
                    toastr.success('Actualizada Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    deleteCompetition(context, competitionId) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.delete("api/v1/admin/competition/delete/" + competitionId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_COMPETITION, data)
                    toastr.success('Eliminada Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({ response }) => {
                    reject(response)
                });
        });
    },
    initParticipantsForCompetition(context, competitionId) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/admin/participants/register_and_non/" + competitionId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_REGISTERED_AND_NON_PARTICIPANTS, data)
                })
                .catch(({ response }) => {
                    reject(response)
                });
        });
    },
    addParticipantToCompetition(context, participantInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/participant/add", participantInfo)
                .then((res) => {
                    if (res.status == 200) {
                        context.commit(type.SET_REGISTERED_AND_NON_PARTICIPANTS, res.data)
                        router.push({name: "CompetitionParticipantRegist", params: participantInfo.competitionId});
                    }
                    toastr.success(res.data.message, '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    registParticipantToCompetition(context, participantInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/participant/regist", participantInfo)
                .then(({data}) => {
                    // console.log(data)
                    context.commit(type.SET_REGISTERED_AND_NON_PARTICIPANTS, data);
                    toastr.success(data.message, '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    getParticipantCategoryOptions(context, participantId) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.get("api/v1/admin/competition/category/participant/" + participantId)
                .then((res) => {
                    resolve(res);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    getModAndCatOfParticipant(context, participantInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/modality/participant", participantInfo)
                .then((res) => {
                    resolve(res);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    getAvailableCategories(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/modality/participant-available-cat", data)
                .then((res) => {
                    resolve(res);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    updateParticipantToCompetition(context, participantInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/participant/update", participantInfo)
                .then((res) => {
                    // console.log(data)
                    context.commit(type.SET_REGISTERED_AND_NON_PARTICIPANTS, res.data);
                    toastr.success('Participante Modificado Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                    resolve(res)
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    unregistParticipantToCompetition(context, participantInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/competition/participant/unregist", participantInfo)
                .then(({data}) => {
                    console.log(data)
                    context.commit(type.SET_REGISTERED_AND_NON_PARTICIPANTS, data);
                    toastr.success('Participante Añadido Correctamente', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    changeStatus(context, statusInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/competition/status/update", statusInfo)
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_ALL_COMPETITION, data)
                    toastr.success("Estado Modificado Correctamente", '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    // console.log(response);
                    reject(response);
                });
        });
    },
};

export default actions;