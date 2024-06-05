<script setup>

import axios from "axios";
import { onMounted, ref } from "vue";
import router from "../../router";

let form = ref({
    id: ""
});

let items = ref([]);

const proprs = defineProps(
    {
        id: {
            type: String,
            defaul: ''
        }
    }
)

const fetchInvoice = async(id) => {
    let response = await axios.get(`/api/invoices/show/${id}`);
    form.value = response.data.invoice;
    items.value = response.data.invoice_items;
}

const print = () => {
    window.print();
    // router.push('/').cache(() => {})
}

const onEdit = (id) => {
    // router.push(`/invoices/edit/${id}`);
    router.push(`/invoices/edit/${id}`);
}

const goHome = () => {
    router.push('/');
}


const deleteInvoice = async (id) => {
    let response = await axios.get(`/api/invoices/delete/${id}`);
    if (response.data.success) {
        router.push('/');
    }
}

onMounted(async () => {
    await fetchInvoice(proprs.id);
});
</script>

<template>
    <div class="container">
    <div class="invoices">
        
        <div class="card__header no-print">
            <div>
                <h2 class="invoice__title">Invoice</h2>
            </div>
            <div>
                
            </div>
        </div>
        <div>
            <div class="card__header--title no-print">
                <h1 class="mr-2">#{{ form.id }}</h1>
            </div>
    
            <div class="no-print">
                <ul  class="card__header-list">
                    <li>
                        <!-- Select Btn Option -->
                        <button class="selectBtnFlat" @click="goHome()">
                            <i class="fas fa-print"></i>
                            Home
                        </button>
                        <!-- End Select Btn Option -->
                    </li>
                    <li>
                        <!-- Select Btn Option -->
                        <button class="selectBtnFlat" @click="print()">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                        <!-- End Select Btn Option -->
                    </li>
                    <li>
                        <!-- Select Btn Option -->
                        <button class="selectBtnFlat" @click="onEdit(form.id)">
                            <i class=" fas fa-reply"></i>
                            Edit
                        </button>
                        <!-- End Select Btn Option -->
                    </li>
                    <li>
                        <!-- End Select Btn Option -->
                    </li>
                    <li>
                        <!-- Select Btn Option -->
                        <button class="selectBtnFlat " @click="deleteInvoice(form.id)">
                            <i class=" fas fa-pencil-alt"></i>
                            Delete
                        </button>
                        <!-- End Select Btn Option -->
                    </li>
                    
                </ul>
            </div>
        </div>

        <div class="table invoice print">
            <div class="logo">
                <!-- <img src="assets/img/logo.png" alt="" style="width: 200px;"> -->
            </div>
            <div class="invoice__header--title">
                <p></p>
                <p class="invoice__header--title-1">Invoice</p>
                <p></p>
            </div>

            
            <div class="invoice__header--item">
                <div>
                    <h2>Invoice To:</h2>
                    <p v-if="form.customer">{{ form.customer.firstname }} {{ form.customer.lastname }}</p>
                </div>
                <div>
                        <div class="invoice__header--item1">
                            <p>Invoice#</p>
                            <span>#{{ form.number }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Date</p>
                            <span>{{ form.date }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Due Date</p>
                            <span>{{ form.due_date}}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Reference</p>
                            <span>{{ form.reference }}</span>
                        </div>
                    
                </div>
            </div>

            <div v-if="items.length > 0">
            <div class="table py1">

                <div class="table--heading3 print__heading">
                    <p>#</p>
                    <p>Item Code</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                </div>
    
                <!-- item 1 -->
                <div class="table--items3" v-for="item in items" :key="item.id">
                    <p># {{ item.id }}</p>
                    <p v-if="item.product">{{ item.product.item_code }}</p>
                    <p>$ {{ item.unit_price }}</p>
                    <p>{{ item.quantity }}</p>
                    <p>$ {{ (item.quantity*item.unit_price).toFixed(2) }}</p>
                </div>


           
            </div>

            <div  class="invoice__subtotal">
                <div>
                    <h2>Thank you for your business</h2>   
                </div>
                <div>
                    <div class="invoice__subtotal--item1">
                        <p>Sub Total</p>
                        <span> $ {{ form.sub_total }}</span>
                    </div>
                    <div class="invoice__subtotal--item2">
                        <p>Discount</p>
                        <span>$ {{ form.discount }}</span>
                    </div>
                    
                </div>
            </div>
            </div>

            <div v-else>
                <div class="text__center">
                    <p>No items found</p>
                </div>
            </div>

            <div class="invoice__total">
                <div>
                    <h2>Terms and Conditions</h2>
                    <p>{{ form.terms_and_conditions }}</p>
                </div>
                <div>
                    <div class="grand__total" >
                        <div class="grand__total--items">
                            <p>Grand Total</p>
                            <span>$ {{ form.total }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card__footer no-print">
            <div>
                
            </div>
            <div>

    
                <router-link to="/" class="btn btn-secondary link__decoration">Go Home</router-link>
            </div>
        </div>
        
    </div>

    </div>
</template>