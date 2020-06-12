import login from './views/login'
import register from './views/register'
export const routes = [
    {
        path: '/login',
        name: 'login',
        component: login
    },
    {
        path: '/register',
        name: 'register',
        component: register
    }
]