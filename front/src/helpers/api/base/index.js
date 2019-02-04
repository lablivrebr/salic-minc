import axios from 'axios';

axios.interceptors.request.use((config) => {
    const user = JSON.parse(localStorage.getItem('user'));
    const conf = config;

    if (user) {
        conf.headers.Authorization = `Bearer ${user.token}`;
    }

    return conf;
}, err => Promise.reject(err));

/* global test, dev */

const API_ENDPOINT = process.env.API === 'test' ? test.API_ENDPOINT : dev.API_ENDPOINT;

const instance = axios.create({
    baseURL: API_ENDPOINT,
});

export const getRequest = (path, queryParams = '') => instance.get(`${path}${queryParams}`);

export const postRequest = (path, data) => instance.post(path, data);

export const putRequest = (path, bodyFormData, id) => instance.post(`${path}/${id}`, bodyFormData);

export const deleteRequest = (path, id) => instance.delete(`${path}/${id}`);
