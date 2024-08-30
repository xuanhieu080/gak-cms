import axios from 'axios';

// const token = localStorage.getItem('tokenUser') ?? null;

// if(token) {
//     axios.defaults.headers.common['Authorization'] = 'Bearer ' + JSON.parse(token);
// }
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-Locale'] = localStorage.hasOwnProperty('locale') ? localStorage.locale : window.AppConfig.defaultLocale;
axios.defaults.headers.common['Content-Type'] = 'application/json';

const instance = axios.create({
    headers: {
        common: {
            'Content-Type': 'application/json', // Đặt giá trị mặc định là 'application/json' cho các yêu cầu khác
        },
        post: {
            'Content-Type': 'multipart/form-data', // Đặt giá trị 'multipart/form-data' cho yêu cầu POST
        },
        put: {
            'Content-Type': 'multipart/form-data', // Đặt giá trị 'multipart/form-data' cho yêu cầu PUT
        },
        patch: {
            'Content-Type': 'multipart/form-data', // Đặt giá trị 'multipart/form-data' cho yêu cầu PATCH
        },
    },
});

export default instance;
