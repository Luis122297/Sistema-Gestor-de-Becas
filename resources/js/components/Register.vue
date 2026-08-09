<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Crear Cuenta</h2>
                <p class="mt-2 text-center text-sm text-gray-600">Sistema de Gestión de Becas</p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="register">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input v-model="form.name" type="text" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <span v-if="errors.name" class="text-xs text-red-500">{{ errors.name[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input v-model="form.email" type="email" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <span v-if="errors.email" class="text-xs text-red-500">{{ errors.email[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input v-model="form.password" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <span v-if="errors.password" class="text-xs text-red-500">{{ errors.password[0] }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                        <input v-model="form.password_confirmation" type="password" required class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>


                </div>

                <div>
                    <button type="submit" :disabled="loading" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-400">
                        {{ loading ? 'Registrando...' : 'Registrarse' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);
const errors = ref({});

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const register = async () => {
    loading.value = true;
    errors.value = {};
    
    try {
        const response = await axios.post('/api/register', form.value);
        localStorage.setItem('auth_token', response.data.access_token);
        localStorage.setItem('user_role', response.data.role);
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
        router.push('/');
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            alert('Error al registrar usuario');
        }
    } finally {
        loading.value = false;
    }
};
</script>