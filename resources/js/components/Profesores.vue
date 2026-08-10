<template>
    <div class="min-h-screen bg-[#FFF9F5] font-sans text-gray-800">
        
        <!-- BARRA DE NAVEGACIÓN -->
        <nav class="bg-gradient-to-r from-[#4b1020] to-[#722F37] shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <img src="/Uptexlogo.png" alt="Logo" class="w-6 h-6 object-contain">
                        </div>
                        <span class="text-white font-bold text-xl tracking-wide">Portal de Becas UPTEX</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        
                        <!-- NOMBRE Y CORREO DEL USUARIO -->
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-white font-bold text-sm leading-tight">{{ userName }}</span>
                            <span class="text-gray-300 text-xs leading-tight">{{ userEmail }}</span>
                        </div>
                        
                        <div class="hidden md:block h-8 w-px bg-[#933b47]"></div>

                        <span class="text-[#FCE5D6] text-sm font-medium capitalize flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                            Rol: {{ userRole.replace('_', ' ') }}
                        </span>
                        
                        <button @click="logout" class="bg-[#2f0913] hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition-colors border border-[#4b1020]">
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in-up">
            
            <!-- ENCABEZADO -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Gestión de Profesores</h2>
                    <p class="text-gray-500 mt-1">Registra, edita o elimina las cuentas de los docentes evaluadores.</p>
                </div>
                <router-link to="/" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2.5 rounded-xl font-bold transition-colors shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </router-link>
            </div>

            <form @submit.prevent="saveProfesor" class="mb-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo</label>
                        <input v-model="form.name" type="text" required class="w-full bg-gray-50 border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors border" placeholder="Ej. Juan Pérez" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Correo Institucional</label>
                        <input v-model="form.email" type="email" required class="w-full bg-gray-50 border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors border" placeholder="profesor@uptex.edu.mx" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Contraseña <span v-if="isEditing" class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input v-model="form.password" type="password" :required="!isEditing" class="w-full bg-gray-50 border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors border" placeholder="••••••••" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end border-t border-gray-100 pt-6">
                    <button v-if="isEditing" @click="resetForm" type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-bold transition-colors">
                        Cancelar Edición
                    </button>
                    <button type="submit" class="bg-[#7A2033] hover:bg-[#561322] text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center">
                        <svg v-if="!isEditing" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ isEditing ? 'Guardar Cambios' : 'Registrar Profesor' }}
                    </button>
                </div>
            </form>

            <div v-if="errorBackend" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ errorBackend }}
            </div>

            <!-- TABLA DE PROFESORES -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre del Docente</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Correo Institucional</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones Administrativas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            <tr v-if="isLoading" class="animate-pulse">
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 font-bold">Cargando base de datos...</td>
                            </tr>
                            <tr v-else-if="profesores.length === 0">
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 font-medium">No hay profesores registrados en el sistema.</td>
                            </tr>
                            <tr v-for="profesor in profesores" :key="profesor.id" class="hover:bg-[#FFF9F5] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ profesor.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ profesor.email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button @click="editProfesor(profesor)" class="bg-[#FCE5D6] text-[#7A2033] hover:bg-[#FAD4BA] px-4 py-2 rounded-lg font-bold transition-colors shadow-sm">
                                            Editar
                                        </button>
                                        <button @click="deleteProfesor(profesor.id)" class="bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 rounded-lg font-bold transition-colors shadow-sm">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const userRole = ref(localStorage.getItem('user_role') || 'jefe_carrera');

const userName = ref('Cargando...');
const userEmail = ref('');
const errorBackend = ref('');
const isLoading = ref(true);

const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
axios.defaults.withCredentials = true;

const profesores = ref([]);
const isEditing = ref(false);
const editingId = ref(null);
const form = ref({
    name: '',
    email: '',
    password: ''
});

onMounted(async () => {
    // 1. Obtener datos del usuario logueado
    try {
        const userRes = await axios.get('/api/user');
        userName.value = userRes.data.name;
        userEmail.value = userRes.data.email;
    } catch (err) {
        userName.value = 'Usuario UPTEX';
    }

    // 2. Cargar los profesores
    await fetchProfesores();
});

const fetchProfesores = async () => {
    isLoading.value = true;
    errorBackend.value = '';
    
    try {
        const response = await axios.get('/api/profesores');
        // Esto arregla el problema si Laravel manda la info envuelta en "data"
        profesores.value = response.data.data ? response.data.data : response.data;
    } catch (error) {
        console.error('Error al cargar profesores:', error);
        
        if (error.response && error.response.status === 500) {
            errorBackend.value = '🚨 ERROR 500: Laravel falló al buscar los profesores. Revisa la línea 13 de tu ProfessorController.php (Está intentando leer "role" de un usuario nulo).';
        } else {
            errorBackend.value = 'No se pudo conectar con la base de datos de profesores.';
        }
    } finally {
        isLoading.value = false;
    }
};

const saveProfesor = async () => {
    try {
        if (isEditing.value) {
            await axios.put(`/api/profesores/${editingId.value}`, form.value);
            alert('Profesor actualizado correctamente.');
        } else {
            await axios.post('/api/profesores', form.value);
            alert('Profesor registrado con éxito.');
        }
        await fetchProfesores();
        resetForm();
    } catch (error) {
        console.error(error);
        alert('Ocurrió un error al guardar. Revisa que el correo no esté duplicado.');
    }
};

const editProfesor = (profesor) => {
    isEditing.value = true;
    editingId.value = profesor.id;
    form.value = {
        name: profesor.name,
        email: profesor.email,
        password: '' 
    };
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteProfesor = async (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar a este profesor de forma permanente?')) {
        try {
            await axios.delete(`/api/profesores/${id}`);
            await fetchProfesores();
        } catch (error) {
            console.error(error);
            alert('No se pudo eliminar al profesor.');
        }
    }
};

const resetForm = () => {
    isEditing.value = false;
    editingId.value = null;
    form.value = { name: '', email: '', password: '' };
};

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (error) {
        console.error('Error al cerrar sesión', error);
    } finally {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_role');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    }
};
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 10px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.4s ease-out forwards;
}
</style>