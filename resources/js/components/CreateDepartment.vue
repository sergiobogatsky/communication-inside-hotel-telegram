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

        <v-btn class="mr-4" @click="submit">create</v-btn>
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

            clear() {
                this.name = '';
                this.telegram_id = '';
            },

            submit() {
                this.errors = {};
                this.$http.post('/api/departments/store'
                    , {
                        name: this.name,
                        telegram_id: this.telegram_id
                    }).then(response => {
                    this.$router.push('/' + response.data.id+ '/' + response.data.name);
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
        }
    }
</script>

<style scoped>

</style>