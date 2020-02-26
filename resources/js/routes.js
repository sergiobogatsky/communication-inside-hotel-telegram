import Vue from 'vue';
import VueRouter from 'vue-router';


import Department from '@/js/components/Department';
import ShowDepartment from '@/js/components/ShowDepartment';
import EditDepartment from '@/js/components/EditDepartment';
import CreateDepartment from '@/js/components/CreateDepartment';
import Employee from '@/js/components/Employee';
import ShowEmployee from '@/js/components/ShowEmployee';
import EditEmployee from '@/js/components/EditEmployee';
import CreateEmployee from '@/js/components/CreateEmployee';
import Tasks from '@/js/components/Tasks';

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
            path: '/departments/show/:id',
            name: 'show department',
            component: ShowDepartment,
            props: true
        },
        {
            path: '/departments/:id/edit',
            name: 'edit department',
            component: EditDepartment,
            props: true
        },
        {
            path: '/departments/create',
            name: 'create department',
            component: CreateDepartment,
            props: true
        },
        {
            path: '/employees',
            name: 'employees',
            component: Employee
        },
        {
            path: '/employees/show/:id',
            name: 'show employee',
            component: ShowEmployee,
            props: true
        },
        {
            path: '/employees/:id/edit',
            name: 'edit employee',
            component: EditEmployee,
            props: true
        },
        {
            path: '/employees/create',
            name: 'create employee',
            component: CreateEmployee,
            props: true
        },
        {
            path: '/tasks',
            name: 'tasks',
            component: Tasks,
        }
    ]
});

export default router;