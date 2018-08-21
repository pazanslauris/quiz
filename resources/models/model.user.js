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
    }

    static fromArray(rawData) {
        let user = new User();
        user.id = rawData.id;
        user.name = rawData.name;

        return user;
    }

    isValid() {
        return (this.id !== null && this.name !== null);
    }
}