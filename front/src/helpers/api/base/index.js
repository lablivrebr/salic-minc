import axios from 'axios';

axios.interceptors.request.use((config) => {
    const user = JSON.parse(localStorage.getItem('user'));
    const conf = config;

    if (user) {
        conf.headers.Authorization = `Bearer ${user.token}`;
    }

    return conf;
}, err => Promise.reject(err));

export const getRequest = (path, queryParams = '') => axios.get(`${path}${queryParams}`);

export const postRequest = (path, data) => axios.post(path, data);

export const putRequest = (path, bodyFormData, id) => axios.post(`${path}/${id}`, bodyFormData);

export const deleteRequest = (path, id) => axios.delete(`${path}/${id}`);
