import ApiService from "@/api/api.service";
import JwtService from "@/common/jwt.service";
import type from './type';

const actions = {
    initCategories(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/categories")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_CATEGORIES, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getCategoryById(context, categoryId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/category/" + categoryId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_CATEGORY, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    createCategory(context, categoryInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/category/create", categoryInfo)
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
    updateCategory(context, categoryInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/category/update", categoryInfo)
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
    deleteCategory(context, categoryId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.delete("api/v1/admin/category/delete/" + categoryId)
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_ALL_CATEGORIES, data)
                    toastr.success('Eliminado Correctamente', '', {timeout: 1000,closeButton: true,closeMethod: 'fadeOut',closeDuration: 300});
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getCategoryOptions(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/category/options")
                .then(({data}) => {
                    // console.log(data);
                    context.commit(type.SET_CATEGORY_OPTIONS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
};

export default actions;