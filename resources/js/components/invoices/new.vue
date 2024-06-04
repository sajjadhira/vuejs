<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

let customers = ref([]);
let customer_id = ref([]);
let items = ref([]);
let carts = ref([]);
let products = ref([]);
const showModal = ref(false);
const hideModal = ref(true);

onMounted(async () => {
    await fetechCustomers();
});

const fetechCustomers = async () => {
    let response = await axios.get("/api/customers")
    customers.value = response.data.customers;
};


const fetechItems = async () => {
    let response = await axios.get("/api/items")
    items.value = response.data.items;
};

const addCart = async (item) => {
    let cart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: item.quantity
    }
    carts.value.push(cart);
};

const openModal = async () => {
    await fetechProducts();
    showModal.value = !showModal.value;
};

const closeModal = () => {
    showModal.value = !hideModal.value;
}

const fetechProducts = async () => {
    let response = await axios.get("/api/products")
    products.value = response.data.products;
};
</script>


<template>
    <div class="container">
      <div class="invoices">
        
        <div class="card__header">
            <div class="text__center">

                <h1>
                    <router-link to="/" class="brand__link">Home</router-link>
                </h1>
                </div>
                <div>
                <h2 class="invoice__title">New Invoice</h2>
                
            </div>
        </div>

        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">Customer</p>
                    <select name="" id="" class="input" v-model="customer_id" placeholder="Select Customer">
                        <option disabled>Select Customer</option>
                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{customer.firstname}} {{ customer.lastname }}</option>
                    </select>
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <input id="date" placeholder="dd-mm-yyyy" type="date" class="input"> <!---->
                    <p class="my-1">Due Date</p> 
                    <input id="due_date" type="date" class="input">
                </div>
                <div>
                    <p class="my-1">Numero</p> 
                    <input type="text" class="input"> 
                    <p class="my-1">Reference(Optional)</p> 
                    <input type="text" class="input">
                </div>
            </div>
            <br><br>
            <div class="table">

                <div class="table--heading2">
                    <p>Item Description</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                    <p></p>
                </div>
    
                <!-- item 1 -->
                <div class="table--items2" v-for="(cart,i) in carts" :key="cart.id">
                    <p>#{{ cart.item_code }} {{ cart.description }}</p>
                    <p>
                        <input type="text" class="input" v-model="cart.unit_price">
                    </p>
                    <p>
                        <input type="text" class="input" v-model="cart.quantity">
                    </p>
                    <p v-if="cart.quantity">
                        $ {{ cart.unit_price * cart.quantity }}
                    </p>
                    <p v-else></p>
                    <p style="color: red; font-size: 24px;cursor: pointer;">
                        &times;
                    </p>
                </div>
                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                </div>
            </div>

            <div class="table__footer">
                <div class="document-footer" >
                    <p>Terms and Conditions</p>
                    <textarea cols="50" rows="7" class="textarea" ></textarea>
                </div>
                <div>
                    <div class="table__footer--subtotal">
                        <p>Sub Total</p>
                        <span>$ 1000</span>
                    </div>
                    <div class="table__footer--discount">
                        <p>Discount</p>
                        <input type="text" class="input">
                    </div>
                    <div class="table__footer--total">
                        <p>Grand Total</p>
                        <span>$ 1200</span>
                    </div>
                </div>
            </div>

           
        </div>
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
                <a class="btn btn-secondary">
                    Save
                </a>
            </div>
        </div>
        
    </div>

      <!--==================== add modal items ====================-->
    <div class="modal main__modal " :class="{show: showModal}">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <div class="modal__items">
                <select class="input my-1">
                    <option disabled>None</option>
                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.item_code }}</option>
                </select>
            </div>
            <br><hr>
            <div class="model__footer">
                <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal ">Save</button>
            </div>
        </div>
    </div>
    
    <br><br><br>
    </div>
</template>