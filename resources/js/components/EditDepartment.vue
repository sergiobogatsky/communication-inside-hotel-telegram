<template>
    <form
            method="post"
    >
        <v-text-field
                v-model="name"
                label="name"
                required
        ></v-text-field>
        <v-text-field
                v-model="telegram_id"
                label="telegram id"
                required
        ></v-text-field>

        <v-btn class="mr-4" @click="submit">update</v-btn>
        <v-btn @click="clear">clear</v-btn>
    </form>
</template>

<script>
    export default {
        props: {
            id: Number,
            name: String,
            telegram_id: Number
        },
        data() {
            return {
                department: Object,
            }
        },
        methods: {
            editDepartment() {

                console.log(this.id + " editDepartment()");
                this.$http.get('/api/departments/' + this.id + '/edit'
                ).then(response => {
                    this.department = response.data;
                    this.id = this.department.id;
                    this.name = this.department.name;
                    this.telegram_id = this.department.telegram_id;
                }, response => {
                    alert('Error in editDepartment()');
                });
            },

            clear() {
                this.name = '';
                this.telegram_id = '';
            },

            submit() {
                this.errors = {};
                this.$http.put('/api/departments/' + this.id + '/update'
                    , {
                        name: this.name,
                        telegram_id: this.telegram_id
                    }).then(response => {
                    this.$router.push('/' + this.id+ '/' + this.name);
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            mounted() {
                this.editDepartment();
            },
        }
    }
</script>

<style scoped>

</style>