import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    initJudge(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/judge")
                .then(({data}) => {
                    console.log(data);
                    for (var j = 0; j < data.judge_heat_scores.length; j++) {
                        for (var i = 1; i < 11; i++) {
                            if (data.judge_heat_scores[j]['wave_' + i] == 0) {
                                data.judge_heat_scores[j]['wave_' + i] ="";
                            }
                            if(data.judge_heat_scores[j]['penal'] == 0)
                                data.judge_heat_scores[j]['penal'] ="";
                        }
                    }
                    console.log(data.judge_heat_scores.length);
                    context.commit(type.GET_JUDGE_ROUND_HEATS, data)
                })
                .catch(({ response }) => {
                    // console.log(response);
                });
        });
    },
    storeHeatResults(context, data) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.post("api/v1/judge/store", data)
                .then((res) => {
                    toastr.success('Puntuación de Manga salvada. Espere a que se active la siguiente manga', '', {timeout: 5000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                    resolve(res)
                })
                .catch(({ response }) => {
                    // console.log(response);
                });
        });
    }
};

export default actions;
