import { createRouter, createWebHistory } from 'vue-router'
import Home from "@/views/Home.vue";
import Login from "@/views/Auth/Login.vue";
import Register from "@/views/Auth/Register.vue";
import Admin from "@/views/Admin/Admin.vue";
import Profile from "@/components/user/profile/Profile.vue";
import UserEdit from "@/views/Admin/manager_user/UserEdit.vue";
import RoleCreate from "@/views/Admin/manager_roles/RoleCreate.vue";
import UserCreate from "@/views/Admin/manager_user/UserCreate.vue";
import RoleEdit from "@/views/Admin/manager_roles/RoleEdit.vue";
import TableUser from "@/components/admin/TableUser.vue";
import TableRole from "@/components/admin/TableRole.vue";
import CreateProfile from "@/views/User/CreateProfile.vue";
import EditProfile from "@/components/user/profile/EditProfile.vue";
import HomeContent from "@/components/user/home/HomeContent.vue";
import TableProfile from "@/components/user/profile/TableProfile.vue";
import CreateAnnonce from "@/components/user/profile/CreateAnnonce.vue";
import TableAnnonce from "@/components/user/profile/TableAnnonce.vue";
import Demande from "@/components/user/home/Demande.vue";
import FormPayment from "@/components/user/home/FormPayment.vue";
import Success from "@/components/user/home/Success.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    /*{
      path: "/search",
      component: Search
    },*/
    {
      path: "/admin",
      component: Admin,
      //redirect: "/dashboard",
      meta:{requiresAuth:true},
      children: [

        {
          path: '/users/:id/edit',
          name: 'users.edit',
          component: UserEdit,
          meta:{requiresAuth:true}
        },
        {
          path: '/users',
          name: 'users.create',
          component: UserCreate,
          meta:{requiresAuth:true}
        },
        {
          path: '/roles',
          name: 'roles.create',
          component: RoleCreate,
          meta:{requiresAuth:true}
        },
        {
          path: '/roles/:id/edit',
          name: 'roles.edit',
          component: RoleEdit,
          meta:{requiresAuth:true}
        },
        {
          path: '/users/index',
          name: 'users.index',
          component: TableUser,
          meta:{requiresAuth:true}
        },
        {
          path: '/roles/index',
          name: 'roles.index',
          component: TableRole,
          meta:{requiresAuth:true}
        }
      ]
    },
    {
      path: '/',
      component: Home,
      children: [
        {
          path: '',
          name: 'home',
          component: HomeContent,
          meta: { requiresAuth: false }
        },
        {
          path: '/annonces/:id',
          name: 'demande.annonce',
          component: Demande,
          meta: { requiresAuth: true }
        },
        {
          path: '/payment/:id',
          name: 'payment',
          component: FormPayment,
          meta: { requiresAuth: true }
        },
        {
          path: '/payment/success',
          name: 'payment.success',
          component: Success,
          meta: { requiresAuth: true }
        },
        {
          path: '/login',
          name: 'login',
          component: Login
        },
        {
          path: '/register',
          name: 'register',
          component: Register
        },

      ]
    },
    {
      path: '/create/profile',
      name: 'create.profile',
      component: CreateProfile,
      meta: { requiresAuth: true }
    },
    {
      path: '/profile',
      component: Profile,
      meta: { requiresAuth: true },
      children: [
        {
          path: '/profile/index',
          name: 'profile.index',
          component: TableProfile,
          meta: { requiresAuth: true }
        },
        {
          path: '/profile/:id/edit',
          name: 'edit.profile',
          component: EditProfile,
          meta: { requiresAuth: true }
        },
        {
          path: '/annonces/create',
          name: 'annonces.create',
          component: CreateAnnonce,
          meta: { requiresAuth: true }
        },
        {
          path: '/annonces/index',
          name: 'annonces.index',
          component: TableAnnonce,
          meta: { requiresAuth: true }
        },
      ]
    }


  ]
})
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const token = localStorage.getItem('token');

  if (requiresAuth && !token) {
    next('/login');
  } else {
    next();
  }
});
export default router
