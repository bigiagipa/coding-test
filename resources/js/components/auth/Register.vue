<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Registration</div>

                <div class="card-body" v-if="!registerSuccess">
                    
                    <!-- errors validation modal -->
                    <form-validation :errors="errorsValidation" ></form-validation>
                    
                    <form @submit.prevent="register()">
                        
                        <div class="form-group">
                        
                            <label for="telephone" class="col-form-label text-md-right">Mobile Number</label>

                            <input id="telephone" type="text" class="form-control" v-model="user.telephone" required autocomplete="telephone" autofocus>

                        </div>

                        <div class="form-group">
                            <label for="firstname" class="col-form-label text-md-right">First name</label>

                            <input id="firstname" type="text" class="form-control " v-model="user.firstname" required autocomplete="firstname" autofocus>

                        </div>

                        <div class="form-group">
                            <label for="lastname" class="col-form-label text-md-right">Last name</label>

                            <input id="lastname" type="text" class="form-control" v-model="user.lastname" required autocomplete="lastname" autofocus>
                           
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">E-Mail Address</label>

                            <input id="email" type="email" class="form-control" v-model="user.email" required autocomplete="email">
                           
                        </div>

                        <div class="form-group ">
                            <label for="dob" class="col-form-label text-md-right">Date of Birth</label>
                            <div class="form-inline">
                                <select class="form-control" v-model="user.dob.month">
                                    <option  value="" selected>Month</option>
                                    <option v-for="(month, index) in months"  :key="index" v-bind:value="index+1">{{ month }}</option>
                                </select>
                                <select class="form-control" v-model="user.dob.date">
                                    <option  value="" selected>Date</option>
                                    <option v-for="d in 31"  :key="d" v-bind:value="d">{{ d }}</option>
                                </select>
                                <select class="form-control" v-model="user.dob.year">
                                    <option  value="" selected>Year</option>
                                    <option v-for="y in 50"  :key="y" v-bind:value=(currentYears-y)>{{ currentYears-y }}</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="gender" class="col-form-label text-md-right">Gender</label>

                            <div class="form-inline">

                                <div class="form-inline">
                                    <input id="gender-Male" type="radio" v-model="user.gender" value="1" class="form-check-input"> <label for="gender-Male" class="form-check-label mr-3">Male</label> 
                                    <input id="gender-Female" type="radio" v-model="user.gender" value="2" class="form-check-input"> <label for="gender-Female" class="form-check-label mr-3">Female</label>
                                </div>

                            
                            </div>
                           
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                Register
                            </button>
                        </div>

                    </form>
                </div>

                <div class="card-body" v-else>
                    <div class="form-group mb-0">
                        <a class="btn btn-primary btn-block" href="/login">Login</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>


<script>
    import $ from 'jquery';

    export default {
        data() {
            return {
                errorsValidation: [],
                user: {
                    telephone: '',
                    firstname: '',
                    lastname: '',
                    email: '',
                    dob: {
                        date: '',
                        month: '',
                        year: ''
                    },
                    gender: '',
                    password: '',
                    password_confirmation: '',
                },
                registerSuccess: false,

                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
            }
        },

        created() {
            
            this.months         = Vue.moment.months();
            this.currentYears   = Vue.moment().year();

        },

        methods: {
            
            register() {

                let loader = Vue.$loading.show({});
                let router = this.$router;
                this.errorsValidation= [];
                // console.log(this.user);
                const { user } = this;
                    // Add
                    axios 
                    .post('/api/register', { user })
                    .then(response => response.data)
                    .then(data => {
                        loader.hide();
                        console.log(data);
                        
                        this.registerSuccess = true;
                    })
                    .catch(err => {
                            loader.hide();  
                            // validation errors
                            if (err.response.status == 422){
                                this.errorsValidation = err.response.data.errors;
                                // console.log(this.errorsValidation);
                            }
                            loader.hide();
                        });
            },

        }
    }
</script>