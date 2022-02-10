import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    initClubs(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/clubs")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_CLUBS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getClubById(context, clubId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/club/" + clubId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_CLUB, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    createClub(context, clubInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/club/create", clubInfo)
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
    updateClub(context, clubInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/club/update", clubInfo)
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
    deleteClub(context, clubId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.delete("api/v1/admin/club/delete/" + clubId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_CLUBS, data)
                    toastr.success('Eliminado Correctamente', '', {timeout: 1000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getClubOptions(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/club/options")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_CLUB_OPTIONS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
};

export default actions;