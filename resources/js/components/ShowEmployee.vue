<template>
    <v-row
            align="start"
            justify="start"
    >
        <v-col
                lg="6"
                xl="6"
                cols="12"
                sm="6"
                md="6"
        >
            <v-card
                    max-width="default"
            >
                <v-card-title>{{employee.telegram_id}}</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col
                                v-if="employee.first_name != null"
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    label="First name"
                                    v-model="employee.first_name"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                v-if="employee.last_name != null"
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    label="Last name"
                                    v-model="employee.last_name"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                v-if="employee.username != null"
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    label="User name"
                                    v-model="employee.username"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    prefix="initialized tasks: "
                                    v-model="employee.initialized_tasks"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    prefix="redirected tasks: "
                                    v-model="employee.redirected_tasks"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    prefix="accepted tasks: "
                                    v-model="employee.accepted_tasks"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                        <v-col
                                cols="12"
                                sm="12"
                                md="6">
                            <v-text-field
                                    prefix="initialized tasks: "
                                    v-model="employee.denied_tasks"
                                    filled
                                    readonly
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <router-link
                            tag="v-btn"
                            text
                            :to="{name: 'edit employee', params: {id: employee.id}}"
                    >
                        edit
                    </router-link>
                    <v-btn
                            text
                            @click="deleteEmployee"
                    >
                        delete
                    </v-btn>

                </v-card-actions>
            </v-card>
        </v-col>
        <v-col
                lg="6"
                xl="6"
                cols="12"
                sm="6"
                md="6"
        >
            <v-card
                    max-width="default"
            >
                <v-card-title>Connected to department(s):</v-card-title>
                <v-card-text>
                    <v-chip
                            v-for="department in departments"
                            class="ma-2"
                            color="indigo"
                            text-color="white"
                    >
                        <v-avatar left>
                            <v-icon>mdi-account-group</v-icon>
                        </v-avatar>
                        {{department.name}}
                    </v-chip>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        props: {
            id: String,
        },
        data() {
            return {
                employee: Object,
            }
        },
        methods: {
            showEmployee(){
                //console.log(this.$route.query);
                //console.log(this.id + " showEmployee()");
                this.$http.get('/api/employees/show/'+this.id

                ).then(response => {
                    this.employee = response.data;
                    this.departments = this.employee.departments;
                }, response => {
                    alert('Error in showEmployee()');
                });
            },
            deleteEmployee() {
                if(confirm("Do you really want to delete?")){
                    this.errors = {};
                    this.$http.delete('/api/employees/' + this.id +'/destroy'
                        , {
                            id: this.id,
                        }).then(response => {
                        this.$router.push('/employees');
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
        },
        mounted(){
            this.showEmployee();
        },
    }
</script>

<style scoped>

</style>