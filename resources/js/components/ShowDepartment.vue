<template>
    <v-row
            align="start"
            justify="start"
    >
        <v-col
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
                            :to="{name: 'edit', params: {id: department.id, name: department.name, telegram_id: department.telegram_id}}"
                    >
                        edit
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
    </v-row>
</template>

<script>
    export default {
        props: {
            id: Number,
        },
        data() {
            return {
                department: Object
            }
        },
        methods: {
            showDepartment(){
                console.log(this.$route.query);
                console.log(this.id + " showDepartment()");
                this.$http.get('/api/departments/show/'+this.id

                ).then(response => {
                    this.department = response.data;
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
        mounted(){
            this.showDepartment();
        },
    }
</script>

<style scoped>

</style>