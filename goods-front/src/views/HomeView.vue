<template>
    <div>
        <h1>Импорт товаров</h1>
        <input type="file" @change="handleFileUpload" ref="file" />
        <button @click="submitFile">Импортировать</button>
    </div>
</template>

<script>
import axios from '@/services/axios';

export default {
    data() {
        return {
        file: null,
        };
    },
    methods: {
        handleFileUpload(event) {
        this.file = event.target.files[0];
        },
        async submitFile() {
        if (!this.file) {
            alert("Пожалуйста, выберите файл");
            return;
        }

        const formData = new FormData();
        formData.append("file", this.file);

        try {
            const response = await axios.post("/import", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            });
            alert(response.data.success);
        } catch (error) {
            alert(error.response.data.error || "Ошибка при импорте товаров.");
        }
        },
    },
};
</script>