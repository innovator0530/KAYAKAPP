import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    initLycras(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/lycras")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_LYCRAS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getLycraById(context, lycraId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/lycra/" + lycraId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_LYCRA, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    createLycra(context, lycraInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/lycra/create", lycraInfo)
                .then((data) => {
                    resolve(data);
                    toastr.success('Creado Correctamente', '', {timeout: 1000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    updateLycra(context, lycraInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/lycra/update", lycraInfo)
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
    deleteLycra(context, lycraId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.delete("api/v1/admin/lycra/delete/" + lycraId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_LYCRAS, data)
                    toastr.success('Eliminado Correctamente', '', {timeout: 1000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getLycraOptions(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/lycra/options")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_LYCRA_OPTIONS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
};

export default actions;