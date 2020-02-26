<template>
    <form
            method="post"
    >
        <v-text-field
                v-model="telegram_id"
                label="telegram id"
                :counter="9"
                required
                v-bind:error-messages="errors"
        ></v-text-field>

        <v-btn class="mr-4" @click="submit">create</v-btn>
        <v-btn @click="clear">clear</v-btn>
    </form>
</template>

<script>
    export default {
        props: {
            id: Number,
            telegram_id: String,
        },
        data() {
            return {
                employee: Object,
                errors: null
            }
        },
        methods: {

            clear() {
                this.telegram_id = '';
            },

            submit() {
                this.errors = {};
                this.$http.post('/api/employees/store'
                    , {
                        telegram_id: this.telegram_id
                    }).then(response => {
                    this.$router.push('/employees/show/' + response.data.id);
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors.telegram_id || {};
                        console.log(this.errors);

                    }
                });
            },
        }
    }
</script>

<style scoped>

</style>