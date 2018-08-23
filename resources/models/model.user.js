export default class User {
    constructor() {
        /**
         * @type {?number}
         */
        this.id = null;

        /**
         * @type {?string}
         */
        this.name = null;

        /**
         * @type {boolean}
         */
        this.isAdmin = false;
    }

    static fromArray(rawData) {
        let user = new User();
        user.id = rawData.id;
        user.name = rawData.name;
        user.isAdmin = rawData.isAdmin;

        return user;
    }

    isValid() {
        return (this.id !== null && this.name !== null);
    }
}