import axios from "axios";


let service = axios.create({
    baseURL: SITE_URL + "/public/api/",
    timeout: 40000,
    headers: {
        Accept: 'application/json',
        'Content-type': 'application/json',
    }
});

const handleSuccess = (response) => {
    return response.data;
}

const handleError = (error) => {
    if (error && error.response && error.response.data.message)
        return Promise.reject(error.response.data.message);
    else if (error.response)
        return Promise.reject(error.response.statusText);
}
service.interceptors.response.use(handleSuccess, handleError);


export default service;
