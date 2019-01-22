import axios from 'axios';
import qs from 'qs'

const BaseUrl = 'http://localhost:8000';
const user = {
    user: localStorage.getItem('user')
};


const baseApi = async (url, params, method) => {
    params = {
        ...params,
        ...user
    };
    let options = {
        url: url,
        method: method,
        baseURL: BaseUrl,
    };
    if (method === 'get') {
        options = {
            params,
            ...options
        }
    } else {
        options = {
            data: qs.stringify(params),
            ...options
        }
    }
    console.log(params);
    try {
        const res = await axios.request(options);
        return res.data;
    } catch (e) {
        throw e.response.msg;
    }
};

const register = params => {
    params = qs.stringify(params);
    return axios.post('register', params, {
        baseURL: BaseUrl
    })
};

const setOvertime = params => {
    return baseApi('overtime', params, 'post')
};

const surplus = () => {
    return baseApi('surplus', [], 'get')
};

export {register, setOvertime, surplus}