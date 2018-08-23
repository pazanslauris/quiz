import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Response from "./models/model.response";

Vue.use(VueAxios, axios);

export default class Api {

    /**
     * @param {string} controllerName
     */
    constructor(controllerName) {
        this.controllerName = controllerName;
    }

    /**
     *
     * @param {string} action
     * @param {{}} [params]
     * @returns {AxiosPromise}
     */
    get(action, params = {}) {
        return Vue.axios.get(this.buildUrl(action), params);
    }

    /**
     *
     * @param action
     * @param params
     * @returns {AxiosPromise}
     */
    post(action, params = {}) {
        return Vue.axios.post(this.buildUrl(action), params);
    }

    /**
     * @param {string} url
     * @returns {string}
     */
    buildUrl(url) {
        return '/' + this.controllerName + '/' + url;
    }

    /**
     * @param action
     * @param params
     * @returns {Promise<any>}
     */
    getResponseAsync(action, params = {}) {
        return new Promise(resolve => {
            this.post(action, params)
                .then(response => {
                    console.log(response);
                    let responseModel = Response.fromArray(response.data);
                    resolve(responseModel);
                })
        })
    }
}
