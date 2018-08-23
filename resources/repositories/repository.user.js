import Api from '../api.js';

class UserRepository {
    constructor() {
        this.quizApi = new Api('UserAjax');
    }

    newUser(name) {
        return this.quizApi.getResponseAsync('register', {name});
    }

    login(name, password) {
        return this.quizApi.getResponseAsync('login', {name, password});
    }

    getUser() {
        return this.quizApi.getResponseAsync('getUser');
    }

    logout() {
        return this.quizApi.getResponseAsync('logout');
    }
}


export default new UserRepository();