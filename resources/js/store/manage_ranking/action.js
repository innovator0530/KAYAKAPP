import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';
import router from '../../router'

const actions = {
    initRankingMenu(context) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.get("api/v1/admin/manage_ranking/all_category_modality")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_CATEGORY_MODALITY, data)
                })
                .catch(({ response }) => {
                    reject(response)
                });
        });
    },
    getCategoryRankingPoints(context, categoryModality) {
        ApiService.setHeader();
        return new Promise((resolve, reject) =>{
            ApiService.post("api/v1/admin/manage_ranking/category_ranking_points", categoryModality)
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_CATEGORY_RANKING_POINTS, data)
                })
                .catch(({ response }) => {
                    reject(response)
                });
        });
    },
    // finalRankingPDF(context, data) {
    //     ApiService.setHeader();
    //     return new Promise((resolve, reject) =>{
    //         ApiService.post("api/v1/admin/manage_ranking/final-ranking-pdf", data)
    //             .then((data) => {
    //                 resolve(data);
    //             })
    //             .catch(({ response }) => {
    //                 reject(response)
    //             });
    //     });
    // }
};

export default actions;