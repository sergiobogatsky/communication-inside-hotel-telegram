<template>
    <v-row
            align="start"
            justify="start"
    >
        <v-col
                v-for="employee in employees"
                lg="3"
                xl="3"
                xs="12"
                sm="4"
                md="4"
        >
            <v-card
                    max-width="344"
            >
                <v-card-title>{{employee.telegram_id}}</v-card-title>
                <v-card-text></v-card-text>
                <v-card-actions>
                    <router-link
                            tag="v-btn"
                            text
                            :to="{name: 'show employee', params: {id: employee.id}}"
                    >
                        show
                    </router-link>

                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        data() {
            return {
                employees: []
            }
        },
        methods: {
            indexEmployees(){
                this.$http.get('/api/employees').then(response => {
                    this.employees = response.data;
                    console.log(this.employees);
                }, response => {
                    alert('Error in indexEmployees()');
                });
            }
        },
        mounted(){
            this.indexEmployees();
        },
    }
</script>