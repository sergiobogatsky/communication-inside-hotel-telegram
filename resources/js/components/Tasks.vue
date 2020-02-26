<template>
        <v-row
                align="start"
                justify="space-around"
        >
            <v-col
                    lg="12"
                    xl="12"
                    cols="12"
                    sm="12"
                    md="12"
            >
                <v-card>
                    <v-card-title>
                        Tasks:
                        <v-spacer></v-spacer>
                        <v-text-field
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                        ></v-text-field>
                    </v-card-title>
                    <v-data-table
                            :headers="headers"
                            :items="tasks"
                            :search="search"
                    ></v-data-table>
                </v-card>
            </v-col>
        </v-row>
</template>

<script>
    export default {
        data() {
            return {
                search: '',
                headers: [
                    {
                        text: 'status',
                        align: 'left',
                        value: 'status',
                    },
                    { text: 'description', value: 'description' },
                    { text: 'initialized employee', value: 'initialized_employee_id' },
                    { text: 'terminated employee', value: 'terminated_employee_id' },
                    { text: 'initialized department', value: 'initialized_department_id' },
                    { text: 'terminated department', value: 'terminated_department_id' },
                    { text: 'created at', value: 'created_at' },
                    { text: 'updated at', value: 'updated_at' },
                    { text: 'terminated at', value: 'terminated_at' },
                ],
                tasks: [],
            }
        },
        methods: {
            getTasks() {
                this.$http.get('/api/tasks/'
                ).then(response => {
                    this.tasks = response.data;
                }, response => {
                    alert('Error in getTasks()');
                });
            },
        },
        created() {
            this.getTasks();
        },
    }
</script>

<style scoped>

</style>