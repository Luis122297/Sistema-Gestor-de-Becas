<template>
    <div class="min-h-screen bg-[#FFF9F5] font-sans text-gray-800">
        
        <nav class="bg-gradient-to-r from-[#4b1020] to-[#722F37] shadow-lg relative z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <img src="/Uptexlogo.png" alt="Logo" class="w-6 h-6 object-contain">
                        </div>
                        <span class="text-white font-bold text-xl tracking-wide hidden sm:block">Portal de Becas UPTEX</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        
                        <div class="hidden md:flex flex-col items-end">
                            <span class="text-white font-bold text-sm leading-tight">{{ userName }}</span>
                            <span class="text-gray-300 text-xs leading-tight">{{ userEmail }}</span>
                        </div>
                        
                        <div class="hidden md:block h-8 w-px bg-[#933b47]"></div>

                        <span class="text-[#FCE5D6] text-sm font-medium capitalize flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                            Rol: {{ (userRole || 'Cargando...').replace('_', ' ') }}
                        </span>

                        <div class="relative ml-2" v-if="userRole === 'alumno'">
                            <button @click="showNotifs = !showNotifs" class="text-white hover:text-[#FAD4BA] transition-colors relative flex items-center">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <span v-if="miEstatus !== 'ninguno'" class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-bounce shadow-md">1</span>
                            </button>

                            <div v-if="showNotifs" class="absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden transform origin-top-right transition-all">
                                <div class="bg-gray-50 p-3 border-b border-gray-100 font-extrabold text-gray-700 text-sm">🔔 Mis Notificaciones</div>
                                <div class="p-4 text-sm text-gray-600">
                                    <div v-if="miEstatus === 'pendiente'" class="flex items-start">
                                        <span class="text-2xl mr-3">⏳</span>
                                        <span>Tu solicitud está en <b>revisión</b> por la Jefatura. Te avisaremos cuando haya un dictamen.</span>
                                    </div>
                                    <div v-else-if="miEstatus === 'aprobada'" class="flex items-start">
                                        <span class="text-2xl mr-3">🎉</span>
                                        <span class="text-green-700">¡Felicidades! Tu beca ha sido <b>aprobada</b>.</span>
                                    </div>
                                    <div v-else-if="miEstatus === 'rechazada'" class="flex items-start">
                                        <span class="text-2xl mr-3">❌</span>
                                        <span class="text-red-600">Lo sentimos, tu solicitud ha sido <b>rechazada</b> en esta ocasión.</span>
                                    </div>
                                    <div v-else class="text-center italic">No tienes notificaciones nuevas.</div>
                                </div>
                            </div>
                        </div>
                        
                        <button @click="logout" class="bg-[#2f0913] hover:bg-black text-white text-sm px-4 py-2 rounded-lg transition-colors border border-[#4b1020] ml-2">
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
                        <p class="text-gray-500 text-sm mt-1">Supervisa expedientes, alumnos y el cuerpo docente.</p>
                    </div>
                    
                    <div class="mt-4 sm:mt-0 flex flex-wrap gap-3 justify-end">
                        <router-link to="/profesores" class="bg-[#7A2033] text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-[#561322] font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Control de Profesores
                        </router-link>

                        <button @click="descargarExcel" class="bg-green-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-green-500 font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Descargar Reporte CSV
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
                                    <p class="text-xs text-[#7A2033] font-semibold bg-[#FCE5D6] inline-block px-2 py-0.5 rounded-md mt-1 uppercase">Beca: {{ notif.scholarship_type }}</p>
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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-extrabold text-gray-900">Alumnos Solicitantes</h3>
                        </div>
                        <div v-if="loading" class="p-8 text-center text-gray-500 animate-pulse">Cargando datos...</div>
                        <div v-else-if="error" class="p-8 text-center text-red-500 font-semibold">{{ error }}</div>
                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Estudiante</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Promedio</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Dictamen</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Porcentaje %</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    <tr v-for="app in applications" :key="app.id" class="hover:bg-[#FFF9F5]">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-bold text-sm text-gray-900">{{ app.student_name }}</div>
                                            <div class="text-xs text-gray-500">{{ app.career_name }}</div>
                                            <div class="text-xs text-[#7A2033] font-bold mt-1 uppercase">Beca: {{ app.scholarship_type }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-black" :class="app.gpa >= 8.5 ? 'text-green-600' : 'text-red-500'">{{ app.gpa }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span v-if="app.meets_academic_criteria" class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-800">Aprobado</span>
                                            <span v-else class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-rose-100 text-rose-800">Rechazado</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center space-x-1">
                                                <input type="number" v-model="app.assigned_percentage" class="w-16 border-gray-300 rounded-lg shadow-sm focus:ring-[#7A2033] focus:border-[#7A2033] text-xs p-1 border" placeholder="Ej. 50">
                                                <button @click="asignarPorcentaje(app.id, app.assigned_percentage)" class="bg-[#FCE5D6] text-[#7A2033] hover:bg-[#FAD4BA] px-2 py-1 rounded-lg text-xs font-bold transition-colors">OK</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit">
                        <div class="p-4 border-b border-gray-100 bg-[#7A2033] flex justify-between items-center">
                            <h3 class="font-extrabold text-white">Cuerpo Docente Activo</h3>
                        </div>
                        <div v-if="loadingProfesores" class="p-4 text-center text-gray-500 text-sm animate-pulse">Cargando profesores...</div>
                        <div v-else class="p-4 space-y-4">
                            <div v-if="resumenProfesores.length === 0" class="text-sm text-gray-500 text-center py-4">No hay profesores en el sistema.</div>
                            
                            <div v-for="profe in resumenProfesores" :key="profe.id" class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold mr-3">
                                    {{ profe.name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-bold text-sm text-gray-900 truncate">{{ profe.name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ profe.email }}</p>
                                </div>
                            </div>
                            
                            <router-link to="/profesores" class="block w-full text-center mt-4 text-sm font-bold text-[#7A2033] hover:text-[#561322] bg-[#FCE5D6] py-2 rounded-lg transition-colors">
                                Administrar Todos
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-else-if="userRole === 'alumno'" class="space-y-6 max-w-4xl mx-auto animate-fade-in-up">
                
                <div v-if="miEstatus !== 'ninguno'" class="bg-white rounded-3xl shadow-xl border border-gray-100 p-12 text-center transform hover:scale-105 transition-transform duration-300">
                    <div class="text-7xl mb-6 animate-bounce">
                        {{ miEstatus === 'pendiente' ? '⏳' : (miEstatus === 'aprobada' ? '🎉' : '📝') }}
                    </div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                        {{ miEstatus === 'pendiente' ? '¡Expediente en Revisión!' : 'Dictamen Finalizado' }}
                    </h2>
                    <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                        {{ miEstatus === 'pendiente' 
                            ? 'Tus datos y documentos han sido enviados con éxito y están seguros en nuestra base de datos. Por favor, mantente atento a la campanita de notificaciones.' 
                            : 'El proceso de tu beca ha concluido. Revisa las notificaciones en la parte superior para ver el resultado.' }}
                    </p>
                    <div class="mt-8">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-[#FCE5D6] text-[#7A2033]">
                            Estatus actual: {{ miEstatus.toUpperCase() }}
                        </span>
                    </div>
                </div>

                <div v-else>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center md:space-x-8 space-y-4 md:space-y-0 relative overflow-hidden mb-6">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#FCE5D6] rounded-bl-full -z-10"></div>
                        <div class="relative cursor-pointer group" @click="triggerPhotoUpload">
                            <div class="w-28 h-28 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border-4 border-white shadow-lg ring-4 ring-[#FCE5D6] transition-transform group-hover:scale-105">
                                <img v-if="fotoPreview" :src="fotoPreview" class="w-full h-full object-cover">
                                <span v-else class="text-gray-400 text-sm font-medium">Subir foto</span>
                            </div>
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
                                <h3 class="text-xl font-bold text-gray-900">Documentos Probatorios</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border-2 border-dashed border-gray-300 bg-gray-50 rounded-xl p-6 text-center hover:bg-gray-100 cursor-pointer transition-colors" @click="triggerKardexUpload">
                                    <div v-if="kardexName">
                                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <p class="text-gray-900 font-bold text-sm">{{ kardexName }}</p>
                                    </div>
                                    <div v-else>
                                        <div class="w-12 h-12 bg-white shadow-sm border border-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 text-[#7A2033]"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg></div>
                                        <p class="text-gray-900 font-medium text-sm mt-2">1. Subir Kardex</p>
                                    </div>
                                    <input type="file" ref="kardexInput" class="hidden" accept="application/pdf" @change="onKardexSelected">
                                </div>

                                <div class="border-2 border-dashed border-[#FAD4BA] bg-[#FFF9F5] rounded-xl p-6 text-center hover:bg-[#FCE5D6] cursor-pointer transition-colors" @click="triggerExtraUpload">
                                    <div v-if="extraName">
                                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <p class="text-gray-900 font-bold text-sm">{{ extraName }}</p>
                                    </div>
                                    <div v-else>
                                        <div class="w-12 h-12 bg-white shadow-sm border border-gray-200 rounded-full flex items-center justify-center mx-auto mb-2 text-[#7A2033]"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                                        <p class="text-gray-900 font-medium text-sm mt-2">2. Comprobante de Gastos o Médico</p>
                                    </div>
                                    <input type="file" ref="extraInput" class="hidden" accept="application/pdf, image/*" @change="onExtraSelected">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center mb-6">
                                <div class="w-8 h-8 rounded-full bg-[#FCE5D6] text-[#7A2033] flex items-center justify-center font-bold mr-3">2</div>
                                <h3 class="text-xl font-bold text-gray-900">Detalles de la Solicitud</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Matrícula (9 dígitos numéricos)</label>
                                    <input v-model="studentForm.matricula" type="text" maxlength="9" pattern="[0-9]{9}" required 
                                        class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors border" 
                                        placeholder="Ej. 123456789"
                                        @input="studentForm.matricula = studentForm.matricula.replace(/[^0-9]/g, '')">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nivel de Estudios</label>
                                    <select v-model="studentForm.career_type" required class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] p-3 border">
                                        <option value="" disabled>Selecciona tu nivel...</option>
                                        <option value="licenciatura">Licenciatura</option>
                                        <option value="ingenieria">Ingeniería</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Promedio Actual (Según Kardex)</label>
                                    <input v-model="studentForm.current_gpa" type="number" step="0.01" min="0" max="10" required 
                                        class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] focus:border-[#7A2033] p-3 shadow-sm transition-colors border" 
                                        placeholder="Ej. 8.5">
                                    <p class="mt-2 text-xs text-red-600 font-bold bg-red-50 p-2 rounded border border-red-100">
                                        * Atención: En caso de que los datos no coincidan con lo del Kardex el alumno será rechazado por datos falsos.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Beca Solicitada</label>
                                    <select v-model="studentForm.scholarship_type" required class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] p-3 border">
                                        <option value="" disabled>Selecciona el tipo de beca...</option>
                                        <option value="promedio">Beca por Excelencia Académica (Promedio)</option>
                                        <option value="socioeconomica">Beca Socioeconómica</option>
                                        <option value="discapacidad">Beca por Discapacidad</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Exposición de motivos</label>
                                    <textarea v-model="studentForm.justification" required rows="4"
                                        class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-[#7A2033] p-3 resize-none border" 
                                        placeholder="Describe brevemente por qué solicitas la beca y justifica el documento extra que subiste..."></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                                <button type="submit" :disabled="isSubmitting" class="bg-[#7A2033] text-white px-8 py-3.5 rounded-xl shadow-lg hover:bg-[#561322] font-bold text-lg transition-all flex items-center">
                                    <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ isSubmitting ? 'Guardando expediente...' : 'Enviar Expediente Oficial' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div v-else-if="userRole === 'profesor'" class="flex items-center justify-center py-20">
                <div class="text-center bg-white p-10 rounded-3xl shadow-sm border border-gray-100 max-w-lg">
                    <div class="w-20 h-20 bg-[#FCE5D6] rounded-full flex items-center justify-center mx-auto mb-6"><span class="text-4xl">📚</span></div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Bienvenido, Profesor</h2>
                    <router-link to="/profesor" class="inline-block bg-[#7A2033] text-white px-8 py-3 rounded-xl shadow-lg hover:bg-[#561322] font-bold text-lg">Ir al Panel de Evaluación</router-link>
                </div>
            </div>

            <div v-else class="flex items-center justify-center py-20">
                <div class="text-center animate-pulse"><p class="text-gray-500 font-bold text-lg">Cargando tu perfil...</p></div>
            </div>
        </main>
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
const userRole = ref(localStorage.getItem('user_role') || null);

const userName = ref('Cargando...');
const userEmail = ref('');

const showNotifs = ref(false);
const miEstatus = ref('ninguno');

const photoInput = ref(null);
const kardexInput = ref(null);
const extraInput = ref(null);

const fotoPreview = ref(null);
const kardexName = ref('');
const extraName = ref('');

const photoFile = ref(null);
const kardexFile = ref(null);
const extraFile = ref(null);
const isSubmitting = ref(false);

const studentForm = ref({
    matricula: '',
    scholarship_type: '',
    career_type: '',
    current_gpa: '',
    justification: ''
});

const applications = ref([]);
const resumenProfesores = ref([]);
const loading = ref(true);
const loadingProfesores = ref(true);
const error = ref(null);

const notificaciones = computed(() => {
    return applications.value.filter(app => app.kardex_url || app.scholarship_type);
});

onMounted(async () => {
    try {
        const userResponse = await axios.get('/api/user');
        userName.value = userResponse.data.name;
        userEmail.value = userResponse.data.email;
        userRole.value = userResponse.data.role;
        localStorage.setItem('user_role', userResponse.data.role);
    } catch (err) {
        userName.value = 'Usuario UPTEX';
    }

    if (userRole.value === 'alumno') {
        try {
            const statusRes = await axios.get('/api/scholarships/my-status');
            miEstatus.value = statusRes.data.status || 'ninguno';
        } catch (error) {
            console.log('Sin solicitud activa');
        }
    } else if (userRole.value === 'jefe_carrera') {
        try {
            const response = await axios.get('/api/scholarships');
            applications.value = response.data.data;
        } catch (err) {
            error.value = 'Error al cargar alumnos.';
        } finally {
            loading.value = false;
        }

        try {
            const response = await axios.get('/api/profesores');
            resumenProfesores.value = response.data;
        } catch (err) {
            console.error('Error al cargar profesores:', err);
        } finally {
            loadingProfesores.value = false;
        }
    } else {
        loading.value = false;
    }
});

const triggerPhotoUpload = () => photoInput.value.click();
const triggerKardexUpload = () => kardexInput.value.click();
const triggerExtraUpload = () => extraInput.value.click();

const onPhotoSelected = (event) => {
    const file = event.target.files[0];
    if (file) { photoFile.value = file; fotoPreview.value = URL.createObjectURL(file); }
};
const onKardexSelected = (event) => {
    const file = event.target.files[0];
    if (file) { kardexFile.value = file; kardexName.value = file.name; }
};
const onExtraSelected = (event) => {
    const file = event.target.files[0];
    if (file) { extraFile.value = file; extraName.value = file.name; }
};

const fileToBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
};

const enviarSolicitud = async () => {
    if (studentForm.value.matricula.length !== 9) {
        alert('La matrícula debe tener exactamente 9 dígitos numéricos.');
        return;
    }

    isSubmitting.value = true;
    try {
        await axios.post('/api/scholarships/my-request', studentForm.value);

        if (photoFile.value || kardexFile.value || extraFile.value) {
            try {
                const payloadBase64 = {};
                if (photoFile.value) payloadBase64.photo = await fileToBase64(photoFile.value);
                if (kardexFile.value) payloadBase64.kardex = await fileToBase64(kardexFile.value);
                if (extraFile.value) payloadBase64.extra_document = await fileToBase64(extraFile.value);
                
                await axios.post('/api/students/my-documents', payloadBase64);
            } catch (fileErr) {
                console.log('Nota de archivos: La solicitud principal se guardó con éxito.');
            }
        }
        
        miEstatus.value = 'pendiente';
        window.scrollTo({ top: 0, behavior: 'smooth' });

    } catch (error) {
        console.error(error);
        alert('Problema al enviar la solicitud. Verifica que todos los campos requeridos estén llenos.');
    } finally {
        isSubmitting.value = false;
    }
};

const descargarExcel = async () => {
    try {
        const response = await axios.get('/api/scholarships/export', { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'reporte_becas_uptex.csv');
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
        alert('Error al guardar el porcentaje.');
    }
};

const logout = async () => {
    try {
        await axios.post('/api/logout');
    } finally {
        localStorage.clear();
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    }
};
</script>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translate3d(0, 10px, 0); }
    to { opacity: 1; transform: translate3d(0, 0, 0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.4s ease-out forwards;
}
</style>