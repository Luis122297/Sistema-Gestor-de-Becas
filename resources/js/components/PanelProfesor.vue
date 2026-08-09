<template>
    <div class="min-h-screen bg-[#FFF9F5] font-sans text-gray-800">
        
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
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Evaluación de Alumnos</h2>
                    <p class="text-gray-500 mt-1">Revisa el perfil de tus alumnos asignados y emite tu recomendación.</p>
                </div>
                <router-link to="/" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2.5 rounded-xl font-bold transition-colors shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </router-link>
            </div>

            <div v-if="!selectedApplication" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alumno</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Matrícula</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Promedio</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estatus</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            <tr v-if="applications.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">No tienes alumnos asignados para evaluar.</td>
                            </tr>
                            <tr v-for="app in applications" :key="app.id" class="hover:bg-[#FFF9F5] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ app.student?.user?.name || app.student_name || 'Alumno' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ app.matricula }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 font-bold">{{ app.current_gpa || app.gpa }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                        {{ app.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="selectApplication(app)" class="bg-[#FCE5D6] text-[#7A2033] hover:bg-[#FAD4BA] px-4 py-2 rounded-lg font-bold transition-colors">
                                        Evaluar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else class="animate-fade-in-up">
                <button @click="goBack" class="mb-6 text-[#7A2033] hover:text-[#561322] font-bold flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Regresar a la lista de alumnos
                </button>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-6">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2">Datos del Solicitante</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div>
                            <span class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Nombre</span>
                            <span class="font-bold text-gray-900">{{ selectedApplication.student?.user?.name || selectedApplication.student_name || 'Alumno' }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Matrícula</span>
                            <span class="font-bold text-gray-900">{{ selectedApplication.matricula }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Promedio</span>
                            <span class="font-bold text-[#7A2033]">{{ selectedApplication.current_gpa || selectedApplication.gpa }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Estatus</span>
                            <span class="capitalize font-bold text-gray-900">{{ selectedApplication.status }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Motivo de la solicitud (Escrito por el alumno)</span>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-gray-700 text-sm">
                            {{ selectedApplication.justification || 'El alumno no proporcionó una justificación detallada.' }}
                        </div>
                    </div>
                </div>

                <form @submit.prevent="saveRecommendation" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-2">Evaluación Docente</h3>
                    
                    <div class="mb-8 space-y-4 bg-[#FFF9F5] p-6 rounded-xl border border-[#FCE5D6]">
                        <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-white rounded-lg transition-colors">
                            <input v-model="form.validated_economy" type="checkbox" class="w-5 h-5 text-[#7A2033] border-gray-300 rounded focus:ring-[#7A2033]" />
                            <span class="text-gray-900 font-bold">He validado la situación económica del alumno</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-white rounded-lg transition-colors">
                            <input v-model="form.validated_disability" type="checkbox" class="w-5 h-5 text-[#7A2033] border-gray-300 rounded focus:ring-[#7A2033]" />
                            <span class="text-gray-900 font-bold">He validado la situación de discapacidad del alumno (Si aplica)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-white rounded-lg transition-colors">
                            <input v-model="form.validated_grades" type="checkbox" class="w-5 h-5 text-[#7A2033] border-gray-300 rounded focus:ring-[#7A2033]" />
                            <span class="text-gray-900 font-bold">He validado el historial de calificaciones (Kardex)</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Comentario de Recomendación</label>
                        <textarea v-model="form.professor_comment" required rows="5" class="w-full bg-gray-50 border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-4 shadow-sm transition-colors border" placeholder="Describe brevemente por qué recomiadas (o no) a este alumno para recibir la beca..."></textarea>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-[#7A2033] hover:bg-[#561322] text-white px-8 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Guardar Evaluación
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const userRole = ref(localStorage.getItem('user_role') || 'profesor');

const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
axios.defaults.withCredentials = true;

const applications = ref([]);
const selectedApplication = ref(null);
const form = ref({
    validated_economy: false,
    validated_disability: false,
    validated_grades: false,
    professor_comment: ''
});

const fetchApplications = async () => {
    try {
        const response = await axios.get('/api/scholarships');
        applications.value = response.data.data || response.data;
    } catch (error) {
        console.error('Error al cargar alumnos:', error);
    }
};

const selectApplication = (app) => {
    selectedApplication.value = app;
    form.value = {
        validated_economy: app.validated_economy === 1 || app.validated_economy === true,
        validated_disability: app.validated_disability === 1 || app.validated_disability === true,
        validated_grades: app.validated_grades === 1 || app.validated_grades === true,
        professor_comment: app.professor_comment || ''
    };
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const goBack = () => {
    selectedApplication.value = null;
    form.value = { validated_economy: false, validated_disability: false, validated_grades: false, professor_comment: '' };
};

const saveRecommendation = async () => {
    try {
        await axios.put(`/api/scholarships/${selectedApplication.value.id}/recommend`, form.value);
        alert('Evaluación guardada correctamente.');
        await fetchApplications();
        goBack();
    } catch (error) {
        console.error(error);
        alert('Ocurrió un error al guardar la recomendación.');
    }
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

onMounted(fetchApplications);
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