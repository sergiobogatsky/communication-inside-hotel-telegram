<template>
    <form
            method="post"
    >
        <v-row
                align="start"
                justify="space-around"
        >
            <v-col

                    lg="5"
                    xl="5"
                    cols="12"
                    sm="12"
                    md="5"
            >
                <v-card

                >
                    <v-card-title>

                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                                v-model="telegram_id"
                                label="telegram id"
                                required
                        ></v-text-field>

                        <v-btn class="mr-4" @click="submit">update</v-btn>
                        <v-btn @click="clear">clear</v-btn>
                    </v-card-text>
                    <v-card-actions>

                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col
                    lg="7"
                    xl="7"
                    cols="12"
                    sm="12"
                    md="7"
            >
                <v-card

                >
                    <v-card-title>Connected to departments:</v-card-title>
                    <v-card-text>
                        <v-row

                        >
                            <v-col
                                    v-for="allDepartment in allDepartments"
                                    cols="12"
                                    sm="4"
                                    md="4"
                            >
                                <v-switch
                                        v-model="allDepartment.in"
                                        :label="allDepartment.name"
                                        color="indigo"
                                        hide-details
                                ></v-switch>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>

                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </form>
</template>

<script>
    export default {
        props: {
            id: Number,
        },
        data() {
            return {
                employee: Object,
                telegram_id: '',
                allDepartments: Array
            }
        },
        methods: {
            editEmployee() {

                console.log(this.id + " editEmployee()");
                this.$http.get('/api/employees/' + this.id + '/edit'
                ).then(response => {
                    this.employee = response.data;
                    this.id = this.employee.id;
                    this.telegram_id = this.employee.telegram_id;
                    this.allDepartments = this.employee.allDepartments;
                    //adding .in = true if employee is inside of department to show it:
                    for (var i = 0, len = this.allDepartments.length; i < len; i++) {
                        this.allDepartments[i].in = false;
                        for (var l = 0, length = this.employee.departments.length; l < length; l++) {
                            if (this.employee.departments[l].name === this.allDepartments[i].name) {
                                this.allDepartments[i].in = true;
                            }
                        }
                    }
                }, response => {
                    alert('Error in editEmployee()');
                });
            },
            clear() {
                this.telegram_id = '';
            },
            submit() {
                console.log(JSON.stringify(this.allDepartments.filter(function (e) {
                    return e.in;
                })));
                this.errors = {};
                this.$http.put('/api/employees/' + this.id
                    , {
                        telegram_id: this.telegram_id,
                        departments: this.allDepartments.filter(function (e) {
                            return e.in;
                        }),
                    }).then(response => {
                    this.$router.push('/employees/');
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
        },
        created() {
            this.editEmployee();
        },
    }
</script>

<style scoped>

</style>