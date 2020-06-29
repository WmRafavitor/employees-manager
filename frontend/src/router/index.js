import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '@/views/Home.vue'
import Login from '@/views/Login.vue';
import Layout from '@/views/Layout.vue';

// Employees views
import EmployeesList from '@/views/Employees/List.vue';
import AddEmployee from '@/views/Employees/Add.vue';
import EditEmployee from '@/views/Employees/Edit.vue';

Vue.use(VueRouter)

  const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      allowAnonymous: true,
    },
  },
  {
    path: '/',
    component: Layout,
    children: [
      {
        path: '',
        name: 'home',
        component: Home
      },
      {
        path: 'employees/new',
        name: 'add-employee',
        component: AddEmployee,
      },
      {
        path: 'employees/edit/:id',
        name: 'edit-employee',
        component: EditEmployee,
      },
      {
        path: 'employees',
        name: 'employees-list',
        component: EmployeesList,
      },
    ]
  }
  // {
  //   path: '/about',
  //   name: 'About',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  // }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
