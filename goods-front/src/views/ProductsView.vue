<template>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Товары</h1>
        <div v-if="products.length === 0" class="text-center text-xl">
            Loading...
        </div>
        <div v-else>
            <div v-for="product in products" :key="product.id">
                <router-link :to="{ name: 'detailProduct', params: { id: product.id } }">
                    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden mb-6">
                        <div v-for="image in product.images" :key="image.id" class="relative">
                            <img :src="`http://127.0.0.1:8000${image.path}`" :alt="product.name" class="w-full h-64 object-cover"/>
                        </div>
                        <div class="p-4">
                            <h2 class="text-xl font-semibold mb-2">{{ product.name }}</h2>
                            <p class="text-gray-700 mb-2">{{ product.description }}</p>
                            <p class="text-lg font-bold mb-4">Price: {{ product.price }} ₽</p>
                        </div>
                    </div>
                </router-link>
            </div>
            <button @click="fetchProducts(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                Назад
            </button>
            <button @click="fetchProducts(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 ml-2">
                Вперед
            </button>
        </div>
    </div>
</template>

<script>
import axios from '@/services/axios';

export default {
    data() {
        return {
            products: [],
            pagination: {
                current_page: 1,
                last_page: 1,
            },
        };
    },
    mounted() {
        this.fetchProducts(this.pagination.current_page);
    },
    methods: {
        fetchProducts(page) {
            axios.get(`/products?page=${page}`).then(response => {
                this.products = response.data.data;
                this.pagination = response.data;

                console.log('Loaded')
            }).catch(error => {
                console.error("Error fetching products:", error);
            });
        },
    },
};
</script>

<style>
.product {
    border: 1px solid #ccc;
    margin: 10px;
    padding: 10px;
}
.pagination {
    margin: 20px 0;
}
</style>
