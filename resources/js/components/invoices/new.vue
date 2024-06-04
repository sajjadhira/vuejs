<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import router from "../../router";


let form = ref({
    customer_id: "",
    date: "",
    due_date: "",
    number: "",
    reference: "",
    discount: "",
    terms: "",
    subtotal: "",
    grandtotal: ""
});

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
    closeModal();
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


const removeCart = (index) => {
    carts.value.splice(index, 1);
};

const subTotal = () => {
    let total = 0;
    carts.value.forEach(cart => {
        total += cart.unit_price * cart.quantity;
    });

    if (isNaN(total)) {
        return 0;
    }

    if (total === 0) {
        return 0;
    }

    return total.toFixed(2);
};

const Total = () => {
    let discount = form.value.discount;
    if (isNaN(discount)) {
        discount = 0;
    }
    let total = subTotal() - discount;


    if (isNaN(total)) {
        return 0;
    }


    return total.toFixed(2);
};


const onSave = async () => {

    if (carts.value.length === 0) {
        alert("Please add items to the invoice");
        return;
    }


    let subtotal = subTotal();
    if (isNaN(subtotal)) {
        alert("Please add items to the invoice");
        return;
    }

    let total = Total();
    if (isNaN(total)) {
        alert("Please add items to the invoice");
        return;
    }

    const formData = new FormData();
    if (Array.isArray(carts.value)) {
    formData.append("invoice_items", JSON.stringify(carts.value));
    formData.append("customer_id", customer_id.value);
    formData.append("date", form.value.date);
    formData.append("due_date", form.value.due_date);
    formData.append("number", form.value.number);
    formData.append("reference", form.value.reference);
    formData.append("discount", form.value.discount);
    formData.append("subtotal", subtotal);
    formData.append("total", total);
    formData.append("terms_and_conditions", form.value.terms);
} else {
  // Handle the case where carts.value is not an array (e.g., show an error message)
  alert("Invoice is not ready to be saved. Please add items to the invoice.");
    return;
}
    let response = await axios.post("/api/invoices/store", formData);

    console.log(response.data);
    carts.value = [];
    router.push('/');
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
                    <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                    <p class="my-1">Due Date</p> 
                    <input id="due_date" type="date" class="input" v-model="form.due_date">
                </div>
                <div>
                    <p class="my-1">Number</p> 
                    <input type="text" class="input" v-model="form.number"> 
                    <p class="my-1">Reference(Optional)</p> 
                    <input type="text" class="input" v-model="form.reference">
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
                    <p>{{cart.id }} # {{ cart.item_code }}</p>
                    <p>
                        <input type="text" class="input" v-model="cart.unit_price">
                    </p>
                    <p>
                        <input type="text" class="input" v-model="cart.quantity">
                    </p>
                    <p v-if="cart.quantity">
                        $ {{ (cart.unit_price * cart.quantity).toFixed(2) }}
                    </p>
                    <p v-else></p>
                    <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeCart(i)">
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
                    <textarea cols="50" rows="7" class="textarea" v-model="form.terms"></textarea>
                </div>
                <div>
                    <div class="table__footer--subtotal">
                        <p>Sub Total</p>
                        <span>$ {{ subTotal() }}</span>
                    </div>
                    <div class="table__footer--discount">
                        <p>Discount</p>
                        <input type="text" class="input" v-model="form.discount">
                    </div>
                    <div class="table__footer--total">
                        <p>Grand Total</p>
                        <span>$ {{ Total() }}</span>
                    </div>
                </div>
            </div>

           
        </div>
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
                <a class="btn btn-secondary" @click="onSave()">
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

                <ul style="list-style: none;">
                    <li v-for="(product,i) in products" :key="product.id" :value="product.id" style="margin-bottom: 5px;">
                    <span>{{ product.id }}# </span>
                    <span style="margin-left: 3px;">{{ product.item_code }}</span>
                    <button @click="addCart(product)" style="border: 1px solid #e0e0e0;width: 35px;height: 35px; margin-left: 3px;">+</button>
                    </li>
                </ul>
                
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