import Vue from 'vue';
import VueRouter from 'vue-router';


import Department from '@/js/components/Department';
import ShowDepartment from '@/js/components/ShowDepartment';
import EditDepartment from '@/js/components/EditDepartment';
import CreateDepartment from '@/js/components/CreateDepartment';

Vue.use(VueRouter);


const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'departments',
            component: Department
        },
        {
            path: '/:id/:name',
            name: 'show',
            component: ShowDepartment,
            props: true
        },
        {
            path: '/:id/:name/edit',
            name: 'edit',
            component: EditDepartment,
            props: true
        },
        {
            path: '/create',
            name: 'create',
            component: CreateDepartment,
            props: true
        },
    ]
})

export default router;