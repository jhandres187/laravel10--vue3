<template>
    <div>
        <header>
            <nav class="flex gap-3 bg-gray-300">
                <router-link :to="{name: 'login'}">Login</router-link>
                <o-button variant="danger" @click="logout()">Logout</o-button>
            </nav>
        </header>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    data(){
        return{
            isLoggedIn: false,
            user: "",
            token: ""
        }
    },
    created(){
        if(window.Laravel.isLoggedIn){
            this.isLoggedIn = true
            this.user = window.Laravel.user
            this.token = window.Laravel.token
        }
    },
    methods: {
        logout(){
            this.$axios.post('/api/user/logout').then((res) => {
                window.location.href = '/vue/login';
            });
        }
    }
}
</script>