<template>
    <form
            method="post"
    >
        <v-row
                align="start"
                justify="space-around"
        >
            <v-col

                    lg="3"
                    xl="3"
                    cols="12"
                    sm="12"
                    md="3"
            >
                <v-card

                >
                    <v-card-title>

                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                                v-model="name"
                                label="name"
                                required
                        >
                        </v-text-field>
                        <v-btn class="mr-4" @click="submit">update</v-btn>
                        <v-btn @click="clear">clear</v-btn>
                    </v-card-text>
                    <v-card-actions>

                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col
                    lg="9"
                    xl="9"
                    cols="12"
                    sm="12"
                    md="9"
            >
                <v-card>
                    <v-card-title>
                        Employees of department:
                        <v-spacer></v-spacer>
                        <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                            v-model="selected"
                            :headers="headers"
                            :items="employees"
                            :search="search"
                            show-select
                    ></v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </form>
</template>

<script>
    export default {
        props: {
            id: String,
        },
        data() {
            return {
                department: Object,
                name: '',
                search: '',
                headers: [
                    {
                        text: 'telegram id',
                        align: 'left',
                        value: 'telegram_id',
                    },
                    { text: 'first name', value: 'first_name' },
                    { text: 'last name', value: 'last_name' },
                    { text: 'username', value: 'username' },
                    { text: 'initialized tasks', value: 'initialized_tasks' },
                    { text: 'redirected tasks', value: 'redirected_tasks' },
                    { text: 'accepted tasks', value: 'accepted_tasks' },
                    { text: 'denied tasks', value: 'denied_tasks' },
                ],
                employees:[],
                selected:[],
            }
        },
        methods: {
            editDepartment() {
                this.$http.get('/api/departments/' + this.id + '/edit'
                ).then(response => {
                    this.department = response.data;
                    this.employees = this.department.allEmployees;
                    this.selected = this.department.employees;
                    console.log(this.selected);
                    this.id = this.department.id;
                    this.name = this.department.name;
                }, response => {
                    alert('Error in editDepartment()');
                });
            },
            clear() {
                this.name = '';
            },
            submit() {
                console.log(this.selected);
                this.errors = {};
                this.$http.put('/api/departments/' + this.id
                    , {
                        name: this.name,
                        employees: this.selected
                    }).then(response => {
                    this.$router.push('/');
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
        },
        created() {
            this.editDepartment();
        },
    }
</script>

<style scoped>

</style>