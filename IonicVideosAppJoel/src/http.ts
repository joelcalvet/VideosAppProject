import Axios from 'axios';

const api = Axios.create({
    baseURL: 'http://localhost:8000/api',
});

api.interceptors.request.use((config: any) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

export function getStorageURL(path: string): string {
    return `http://localhost:8000/storage/${path}`;
}

export default api;