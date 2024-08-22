import ModelService from "@/services/ModelService";

export default class UserService extends ModelService {

    constructor() {
        super();
        this.url = '/users';
    }

    public updateAvatar(id, payload) {
        const formData = new FormData();
        formData.append("image", payload.image);
        formData.append('_method', 'PUT');
        return this.post(`/users/${id}/avatar`, formData);
    }

}
