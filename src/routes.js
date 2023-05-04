import { Navigate, useNavigate, useRoutes } from 'react-router-dom';
import { useEffect } from 'react';
import Swal from 'sweetalert2';
// layouts
import DashboardLayout from './layouts/dashboard';
import SimpleLayout from './layouts/simple';
//
import BlogPage from './pages/BlogPage';
import UserPage from './pages/UserPage';
import LoginPage from './pages/LoginPage';
import Page404 from './pages/Page404';
import ProductsPage from './pages/ProductsPage';
import DashboardAppPage from './pages/DashboardAppPage';
import RegisterPage from './pages/RegisterPage';
import ShowPdf from './pages/ShowPdf';
import UserTable from './sections/@dashboard/user/userTable';
import ListUser from './sections/@dashboard/users/ListUser';

// ----------------------------------------------------------------------

export default function Router() {
  const navigate = useNavigate();
  const routes = useRoutes([
    {
      path: '/dashboard',
      element: <DashboardLayout />,
      children: [
        { element: <Navigate to="/dashboard/app" />, index: true },
        { path: 'app', element: <DashboardAppPage /> },
        { path: 'user', element: <UserPage />, children: [{ path: 'userTable', element: <UserTable /> }] },
        { path: 'products', element: <ProductsPage /> },
        { path: 'blog', element: <BlogPage /> },
        { path: 'userTable', element: <UserTable /> },
        { path: 'check', element: <ListUser /> },
        { path: 'pdf', element: <ShowPdf /> },

        // {path: 'userTable' element: }
      ],
    },
    {
      path: 'login',
      element: <LoginPage />,
    },
    {
      path: 'register',
      element: <RegisterPage />,
    },
    {
      element: <SimpleLayout />,
      children: [
        { element: <Navigate to="/dashboard/app" />, index: true },
        { path: '404', element: <Page404 /> },
        { path: '*', element: <Navigate to="/404" /> },
      ],
    },
    {
      path: '*',
      element: <Navigate to="/404" replace />,
    },
  ]);

  return routes;
}
