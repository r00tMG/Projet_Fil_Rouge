import axios from 'axios';

const apiInstance = axios.create({
    baseURL: 'https://backend-mongp.mayaapps.site/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});
export default apiInstance;
