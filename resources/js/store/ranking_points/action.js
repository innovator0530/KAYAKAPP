import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    getRankingPointsById(context, rankingId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/ranking_points/" + rankingId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_RANKING_POINTS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getAllRankings(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/rankings/")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_RANKINGS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getRankingById(context, rankingId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/ranking/" + rankingId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_RANKING, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    updateRanking(context, rankingInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/ranking/update", rankingInfo)
                .then((data) => {
                    resolve(data);
                    toastr.success('Actualizado Correctamente', '', {timeout: 1000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
};

export default actions;