<template>
    <v-row
            align="start"
            justify="start"
    >
        <v-col
                v-for="department in departments"
                lg="3"
                xl="3"
                xs="12"
                sm="4"
                md="4"
        >
            <v-card
                    max-width="344"
            >
                <v-card-title>{{department.name}}</v-card-title>
                <v-card-text>{{department.telegram_id}}</v-card-text>
                <v-card-actions>
                    <router-link
                            tag="v-btn"
                            text
                            :to="{name: 'show', params: {id: department.id, name: department.name}}"
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
                departments: []
            }
        },
        methods: {
            indexDepartments(){
                this.$http.get('/api/departments').then(response => {
                    this.departments = response.data;
                    console.log(this.departments);
                }, response => {
                    alert('Error in indexDepartments()');
                });
            }
        },
        mounted(){
            this.indexDepartments();
        },
    }
</script>