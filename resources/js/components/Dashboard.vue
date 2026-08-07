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
                        
                        <button @click="activar2FA" class="bg-[#FCE5D6] hover:bg-[#FAD4BA] text-[#7A2033] text-sm px-4 py-2 rounded-lg transition-colors border border-[#FAD4BA] font-bold shadow-sm">
                            Configurar 2FA
                        </button>

                        <button @click="logout" class="bg-[#2f0913] hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition-colors border border-[#4b1020]">
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div v-if="userRole === 'jefe_carrera'" class="space-y-6 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Administración General</h2>
                        <p class="text-gray-500 text-sm mt-1">Gestiona y asigna los porcentajes de beca a los alumnos evaluados.</p>
                    </div>
                    
                    <!-- AQUÍ ESTÁ EL NUEVO CONTENEDOR DE BOTONES PARA LA JEFA -->
                    <div class="mt-4 sm:mt-0 flex flex-wrap gap-3 justify-end">
                        <router-link to="/profesores" class="bg-[#7A2033] text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-[#561322] hover:shadow-lg transition-all flex items-center transform hover:-translate-y-0.5 font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Gestión de Profesores
                        </router-link>

                        <button @click="descargarExcel" class="bg-green-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-green-500 hover:shadow-lg transition-all flex items-center transform hover:-translate-y-0.5 font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Descargar Reporte .xls
                        </button>
                    </div>
                </div>

                <div v-if="notificaciones.length > 0" class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-yellow-200 p-5 rounded-2xl shadow-sm">
                    <h3 class="text-lg font-bold text-yellow-800 flex items-center mb-4">
                        <span class="text-xl mr-2">🔔</span> Expedientes Recientes
                        <span class="ml-3 bg-yellow-500 text-white text-xs px-2.5 py-1 rounded-full shadow-sm">{{ notificaciones.length }} nuevos</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="notif in notificaciones" :key="'notif-'+notif.id" class="bg-white p-5 rounded-xl border border-yellow-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-gray-900">{{ notif.student_name }}</p>
                                    <p class="text-xs text-[#7A2033] font-semibold bg-[#FCE5D6] inline-block px-2 py-0.5 rounded-md mt-1">Pide {{ notif.requested_percentage }}%</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 italic bg-gray-50 p-2 rounded-lg border border-gray-100">"{{ notif.justification }}"</p>
                            <div class="flex space-x-2 mt-4">
                                <a v-if="notif.photo_url" :href="notif.photo_url" target="_blank" class="flex-1 text-center text-xs bg-[#FCE5D6] text-[#7A2033] px-3 py-2 rounded-lg hover:bg-[#FAD4BA] font-bold transition-colors">
                                     Ver Foto
                                </a>
                                <a v-if="notif.kardex_url" :href="notif.kardex_url" target="_blank" class="flex-1 text-center text-xs bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 font-bold transition-colors">
                                     📄 Ver Kardex
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div v-if="loading" class="p-8 text-center text-gray-500 animate-pulse">Cargando datos de los alumnos...</div>
                    <div v-else-if="error" class="p-8 text-center text-red-500 font-semibold">{{ error }}</div>
                    
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Carrera</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perfil Acad.</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sistema</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dictamen Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                <tr v-for="app in applications" :key="app.id" class="hover:bg-[#FFF9F5] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ app.student_name }}</div>
                                        <div class="text-xs text-gray-500">al{{ app.id }}000@alumno.uptex.edu.mx</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ app.career_name }}</div>
                                        <div class="text-xs text-gray-500 capitalize">{{ app.career_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-black" :class="app.gpa >= 8.5 ? 'text-green-600' : 'text-red-500'">{{ app.gpa }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="app.meets_academic_criteria" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800">Aprobado</span>
                                        <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-rose-100 text-rose-800">Rechazado</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <input type="number" v-model="app.assigned_percentage" class="w-20 border-gray-300 rounded-lg shadow-sm focus:ring-[#7A2033] focus:border-[#7A2033] sm:text-sm p-2 border" :placeholder="app.requested_percentage || '0'">
                                            <span class="text-gray-500 font-bold">%</span>
                                            <button @click="asignarPorcentaje(app.id, app.assigned_percentage)" class="bg-[#FCE5D6] text-[#7A2033] hover:bg-[#FAD4BA] px-3 py-1.5 rounded-lg text-sm font-bold transition-colors">Guardar</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div v-else-if="userRole === 'alumno'" class="space-y-6 max-w-4xl mx-auto animate-fade-in-up">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center md:space-x-8 space-y-4 md:space-y-0 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#FCE5D6] rounded-bl-full -z-10"></div>
                    <div class="relative cursor-pointer group" @click="triggerPhotoUpload">
                        <div class="w-28 h-28 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border-4 border-white shadow-lg ring-4 ring-[#FCE5D6] transition-transform group-hover:scale-105">
                            <img v-if="fotoPreview" :src="fotoPreview" class="w-full h-full object-cover">
                            <span v-else class="text-gray-400 text-sm font-medium">Subir foto</span>
                        </div>
                        <button class="absolute bottom-0 right-0 bg-[#7A2033] text-white rounded-full p-2.5 shadow-lg hover:bg-[#561322] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>
                        <input type="file" ref="photoInput" class="hidden" accept="image/png, image/jpeg, image/jpg" @change="onPhotoSelected">
                    </div>
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold text-gray-900">Mi Expediente</h2>
                        <p class="text-gray-500 mt-2">Sube tus documentos para iniciar el proceso de validación de beca.</p>
                    </div>
                </div>

                <form @submit.prevent="enviarSolicitud" class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 rounded-full bg-[#FCE5D6] text-[#7A2033] flex items-center justify-center font-bold mr-3">1</div>
                            <h3 class="text-xl font-bold text-gray-900">Documento Probatorio</h3>
                        </div>
                        <div class="border-2 border-dashed border-[#FAD4BA] bg-[#FFF9F5] rounded-xl p-10 text-center hover:bg-[#FCE5D6] cursor-pointer transition-colors" @click="triggerKardexUpload">
                            <div v-if="kardexName">
                                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-gray-900 font-bold text-lg">{{ kardexName }}</p>
                                <p class="text-sm text-[#7A2033] mt-1 font-medium">Clic para reemplazar el archivo</p>
                            </div>
                            <div v-else>
                                <div class="w-16 h-16 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-[#7A2033]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p class="text-gray-900 font-medium text-lg mt-2">Sube tu Kardex Actualizado</p>
                                <p class="text-gray-500 text-sm mt-1">Formatos permitidos: PDF (Máx. 5MB)</p>
                            </div>
                            <input type="file" ref="kardexInput" class="hidden" accept="application/pdf" @change="onKardexSelected">
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 rounded-full bg-[#FCE5D6] text-[#7A2033] flex items-center justify-center font-bold mr-3">2</div>
                            <h3 class="text-xl font-bold text-gray-900">Detalles de la Solicitud</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Porcentaje de descuento solicitado</label>
                                <select v-model="studentForm.requested_percentage" required class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors">
                                    <option value="" disabled>Selecciona el nivel de apoyo...</option>
                                    <option value="25">25% - Apoyo básico para transporte/material</option>
                                    <option value="50">50% - Apoyo medio (Situación económica vulnerable)</option>
                                    <option value="75">75% - Apoyo alto (Riesgo de deserción)</option>
                                    <option value="100">100% - Beca completa (Casos excepcionales)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Exposición de motivos</label>
                                <textarea v-model="studentForm.justification" required rows="4"
                                    class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors resize-none" 
                                    placeholder="Describe brevemente por qué solicitas la beca..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" :disabled="isSubmitting" class="bg-[#7A2033] text-white px-8 py-3.5 rounded-xl shadow-lg hover:bg-[#561322] hover:shadow-xl disabled:bg-gray-300 disabled:shadow-none font-bold text-lg transition-all transform hover:-translate-y-0.5 flex items-center">
                                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ isSubmitting ? 'Enviando...' : 'Enviar Expediente Oficial' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div v-else-if="userRole === 'profesor'" class="flex items-center justify-center py-20">
                <div class="text-center bg-white p-10 rounded-3xl shadow-sm border border-gray-100 max-w-lg">
                    <div class="w-20 h-20 bg-[#FCE5D6] rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl"></span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Bienvenido, Profesor</h2>
                    <p class="text-gray-600 mb-8 text-lg">Su módulo de evaluación de alumnos se encuentra en la sección de Grupos.</p>
                    <router-link to="/profesor" class="inline-block bg-[#7A2033] text-white px-8 py-3 rounded-xl shadow-lg hover:bg-[#561322] transition-all font-bold text-lg hover:-translate-y-0.5">
                        Ir al Panel de Evaluación
                    </router-link>
                </div>
            </div>

        </main>

        <div v-if="show2FAModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-white p-8 rounded-3xl shadow-2xl max-w-sm w-full text-center relative">
                <button @click="show2FAModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 font-bold text-xl">&times;</button>
                <div class="w-16 h-16 bg-[#FCE5D6] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">🔐</div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Configura tu 2FA</h3>
                <p class="text-sm text-gray-500 mb-6">Abre Google Authenticator y escanea este código. En tu próximo inicio de sesión, te pediremos el código de 6 dígitos.</p>
                
                <div class="flex justify-center mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100" v-html="qrCodeSvg"></div>
                
                <p class="text-xs text-gray-400 mb-6">Clave secreta: <span class="font-mono text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ qrSecret }}</span></p>
                
                <button @click="show2FAModal = false" class="w-full bg-[#7A2033] text-white px-4 py-3 rounded-xl font-bold hover:bg-[#561322] shadow-lg transition-transform hover:-translate-y-0.5">
                    ¡Listo, ya lo guardé!
                </button>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
axios.defaults.withCredentials = true; 

const router = useRouter();
const userRole = ref(localStorage.getItem('user_role'));
const applications = ref([]);
const loading = ref(true);
const error = ref(null);

const photoInput = ref(null);
const kardexInput = ref(null);
const fotoPreview = ref(null);
const kardexName = ref('');
const photoFile = ref(null);
const kardexFile = ref(null);
const isSubmitting = ref(false);

const show2FAModal = ref(false);
const qrCodeSvg = ref('');
const qrSecret = ref('');

const studentForm = ref({
    requested_percentage: '',
    justification: ''
});

const notificaciones = computed(() => {
    return applications.value.filter(app => app.kardex_url || app.requested_percentage);
});

onMounted(async () => {
    if (userRole.value === 'jefe_carrera') {
        try {
            const response = await axios.get('/api/scholarships');
            applications.value = response.data.data;
        } catch (err) {
            error.value = 'Hubo un error al cargar la base de datos.';
        } finally {
            loading.value = false;
        }
    } else {
        loading.value = false;
    }
});

const activar2FA = async () => {
    try {
        const response = await axios.post('/api/2fa/generate');
        qrCodeSvg.value = response.data.qr_code_svg;
        qrSecret.value = response.data.secret;
        show2FAModal.value = true;
    } catch (error) {
        alert('Error al generar el código QR.');
    }
};

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (error) {
        console.error('Error al cerrar sesión en el servidor', error);
    } finally {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_role');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    }
};

const descargarExcel = async () => {
    try {
        const response = await axios.get('/api/scholarships/export', { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'reporte_becas_uptex.xlsx'); 
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    } catch (error) {
        alert('Error al descargar el reporte.');
    }
};

const asignarPorcentaje = async (id, porcentaje) => {
    if (!porcentaje || porcentaje < 0 || porcentaje > 100) {
        return alert('Ingresa un porcentaje válido entre 0 y 100');
    }
    try {
        await axios.put(`/api/scholarships/${id}/assign-percentage`, { assigned_percentage: porcentaje });
        alert('Porcentaje asignado correctamente');
    } catch (error) {
        alert('Error al guardar el porcentaje. Verifica tu conexión.');
    }
};

const triggerPhotoUpload = () => photoInput.value.click();
const triggerKardexUpload = () => kardexInput.value.click();

const onPhotoSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        photoFile.value = file;
        fotoPreview.value = URL.createObjectURL(file);
    }
};

const onKardexSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        kardexFile.value = file;
        kardexName.value = file.name;
    }
};

const enviarSolicitud = async () => {
    isSubmitting.value = true;
    try {
        await axios.post('/api/scholarships/my-request', studentForm.value);

        if (photoFile.value || kardexFile.value) {
            const formData = new FormData();
            if (photoFile.value) formData.append('photo', photoFile.value);
            if (kardexFile.value) formData.append('kardex', kardexFile.value);
            
            await axios.post('/api/students/my-documents', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        
        alert('¡Tu expediente y justificación han sido enviados con éxito a la Jefatura de Carrera!');
    } catch (error) {
        alert('Error al enviar los documentos. Verifica que el Kardex sea PDF y menor a 5MB.');
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style>
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