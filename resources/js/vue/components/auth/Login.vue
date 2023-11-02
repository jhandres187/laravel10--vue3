<template>
    <section>
        <h2 class="text-center text-3xl font-bold my-8">Login</h2>
        <form @submit.prevent="submit">
            <o-field label="Username" :variant="errors.email ? 'danger' : 'primary'" :message="errors.email">
                <o-input v-model="form.email"></o-input>
            </o-field>
            <o-field label="password" :variant="errors.password ? 'danger' : 'primary'" :message="errors.password">
                <o-input v-model="form.password" type="password"></o-input>
            </o-field>
            <o-field  :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                <o-button variant="primary" native-type="submit">Send</o-button>
            </o-field>
        </form>
    </section>
</template>
<script>
export default {
    data(){
        return{
            form: {
                email: '',
                password: '',
            },
            errors: {
                login: "",
            }
        }
    },
    created(){
        if(this.$root.isLoggedIn){
            this.$router.push({name : 'list'})
        }
    },
    methods: {
        cleanErrorsForm(){
            this.errors.login = ""
        },
        submit(){
            this.cleanErrorsForm();
            this.$axios.post('/api/user/login', this.form).then((res) => {
                setTimeout(() => {window.location.href = "/vue"}, 1500);
                this.$root.setCookiesAuth(res.data);
                this.$oruga.notification.open({
                    message: 'Login Exitoso',
                    position: 'top-right',
                    variant: 'success',
                    duration: 1000,
                    closable: true
                });
            }).catch((error) => {
                console.log(error)
                if(error.response.data){
                    this.errors.email = error.response.data.message
                }
            });
        }
    }
}
</script>