import Api from '../api.js';
import Response from "../models/model.response";

class UserRepository {
    constructor() {
        this.quizApi = new Api('UserAjax');
    }

    newUser(name) {
        return new Promise(resolve => {
            this.quizApi.post('register', {name})
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }

    getUser() {
        return new Promise(resolve => {
            this.quizApi.post('getUser')
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }

    logout() {
        return new Promise(resolve => {
            this.quizApi.post('logout')
                .then(response => {
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }
}


export default new UserRepository();