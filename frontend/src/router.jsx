import { createBrowserRouter } from 'react-router-dom'
import App from './App'
import Login from './Login';
import Register from './Register';
import AdminLayout from './admin/AdminLayout';
import AdminDashboard from './admin/Dashboard';
import UserLayout from './user/UserLayout';
import UserDashboard from './user/Dashboard';

const router = createBrowserRouter([
    {
        path: '/',
        element: <App/>
    },
    {
        path: '/login',
        element: <Login/>
    },
    {
        path: '/register',
        element: <Register/>
    },
    {
        path: '/admin-dashboard',
        element: <AdminLayout/>,
        // children: [
        //    {
        //      path: '/admin',
        //      element: <AdminDashboard/>
        //     }
        // ]
    },
    {
        path: '/dashboard',
        element: <UserLayout/>,
        // children: [
        //    {
        //      path: '/user',
        //      element: <UserDashboard/>
        //     }
        // ]
    }
])

export default router;