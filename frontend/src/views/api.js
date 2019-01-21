import axios from 'axios';

const BaseUrl = 'localhost:8000';
const user = {
    user: localStorage.getItem('user')
};

const register = user => {
    return axios.get('register', user)
};

const setOvertime = params => {
    params = {
        params,
        ...user
    };
    return axios.post('overtime', params)
};