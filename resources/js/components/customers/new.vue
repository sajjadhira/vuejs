<script setup>

import axios from 'axios';
import { ref } from 'vue';
import router from "../../router";
let customer = ref({
    firstname: '',
    lastname: '',
    email: '',
    address: ''
});

const storeCustomer = async () => {
    
    let formData = new FormData();
    formData.append('firstname', customer.value.firstname);
    formData.append('lastname', customer.value.lastname);
    formData.append('email', customer.value.email);
    formData.append('address', customer.value.address);

    let response = axios.post('/api/customers/store', formData);
    
    router.push('/invoices/new');
}



</script>


<template>
    <div class="container">
        <h1 class="text__center main__title">New Customer</h1>

        <div class="customer__form">
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" id="name" v-model="customer.firstname" class="form-control">
            </div>
            <div class="form-group">
                <label for="name">Last Name</label>
                <input type="text" id="name" v-model="customer.lastname" class="form-control">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" v-model="customer.email" class="form-control">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" v-model="customer.address" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" @click="storeCustomer()">Save</button>
                <button class="btn btn-primary" @click="goHome()">Home</button>


            </div>



            </div>


    </div>
</template>