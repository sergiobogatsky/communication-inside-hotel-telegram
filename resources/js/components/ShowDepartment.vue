<template>
    <v-row
            align="start"
            justify="start"
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
                <v-card-title>{{department.name}}</v-card-title>
                <v-card-text>

                </v-card-text>
                <v-card-actions>
                    <router-link
                            tag="v-btn"
                            text
                            :to="{name: 'edit department', params: {id: department.id}}"
                    >
                        edit and add employees
                    </router-link>
                    <v-btn
                            text
                            @click="deleteDepartment"
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
                <v-card-title>Employee(s) connected:</v-card-title>
                <v-card-text>
                    <v-chip
                            v-for="employee in employees"
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                    >
                        {{employee.telegram_id}}
                        <v-icon right>mdi-account-outline</v-icon>
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
                department: '',
                employees: '',
            }
        },
        methods: {
            showDepartment(){
                console.log(this.$route.query);
                console.log(this.id + " showDepartment()");
                this.$http.get('/api/departments/show/'+this.id

                ).then(response => {
                    this.department = response.data;
                    this.employees = response.data.employees;
                    console.log(response.data);
                }, response => {
                    alert('Error in showDepartment()');
                });
            },
            deleteDepartment() {
                if(confirm("Do you really want to delete?")){
                    this.errors = {};
                    this.$http.delete('/api/departments/' + this.id +'/destroy'
                        , {
                            id: this.id,
                        }).then(response => {
                        this.$router.push('/');
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },
        },
        created(){
            this.showDepartment();
        },
    }
</script>

<style scoped>

</style>