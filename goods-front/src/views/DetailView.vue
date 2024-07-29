<template>
    <div class="container mx-auto p-4">
        <div v-if="loading" class="text-center text-xl">
            Loading...
        </div>
        <div v-else-if="product" class="flex gap-10 bg-white shadow-lg rounded-lg p-6">
            <div>
            <div class="relative mb-4">
                <img :src="`http://127.0.0.1:8000${product.images[0]?.path}`" :alt="product.name" class="w-full h-full object-cover rounded"/>
            </div>
            </div>
            <div class="text-start">
            <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
            <p class="text-gray-700 mb-4">{{ product.description }}</p>
            <p class="text-lg font-bold mb-4">Price: {{ product.price }} ₽</p>

            <div v-if="product.attributes.length" class="mt-6">
                <h2 class="text-2xl font-semibold mb-2">Additional Information:</h2>
                <ul class="list-disc list-inside space-y-2">
                <li v-for="attribute in product.attributes" :key="attribute.id">
                    <strong>{{ formatKey(attribute.key) }}:</strong> {{ attribute.value }}
                </li>
                </ul>
            </div>
            </div>
        </div>
        <div v-else class="text-center text-xl">
            Продукт не найден.
        </div>
        <button @click="goBack" class="bg-blue-500 text-white py-2 px-4 mt-5 rounded hover:bg-blue-600">
            Вернуться
        </button>
    </div>
</template>
  
<script>
import axios from '@/services/axios';

export default {
    props: {
        id: {
            type: [String, Number],
            required: true,
        },
    },
    data() {
        return {
            product: null,
            loading: true,
        };
    },
    async mounted() {
        await this.fetchProduct();
    },
    methods: {
        async fetchProduct() {
            try {
            const response = await axios.get(`/products/${this.id}`);
            this.product = response.data;
            } catch (error) {
            console.error("Error fetching product:", error);
            } finally {
            this.loading = false;
            }
        },
        goBack() {
            this.$router.back();
        },
        formatKey(key) {
            const keyMap = {
            'dop_pole_razmer': 'Размер',
            'dop_pole_cvet': 'Цвет',
            'dop_pole_brend': 'Бренд',
            'dop_pole_sostav': 'Состав',
            'dop_pole_kol_vo_v_upakovke': 'Кол-во в упаковке',
            'dop_pole_ssylka_na_upakovku': 'Ссылка на упаковку',
            'dop_pole_seo_title': 'SEO Заголовок',
            'dop_pole_seo_h1': 'SEO H1',
            'dop_pole_seo_description': 'SEO Описание',
            'dop_pole_ves_tovarag': 'Вес',
            'dop_pole_sirinamm': 'Ширина',
            'dop_pole_vysotamm': 'Высота',
            'dop_pole_dlinamm': 'Длина',
            'dop_pole_ves_upakovkig': 'Вес упаковки',
            'dop_pole_sirina_upakovkimm': 'Ширина упаковки',
            'dop_pole_vysota_upakovkimm': 'Высота упаковки',
            'dop_pole_dlina_upakovkimm': 'Длина упаковки',
            'dop_pole_kategoriia_tovara': 'Категория'
            };
            return keyMap[key] || key;
        }
    },
};
</script>
  
<style scoped>

</style>
  