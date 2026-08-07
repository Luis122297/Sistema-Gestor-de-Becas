<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Gestión de Profesores</h2>

    <form @submit.prevent="saveProfesor" class="mb-6 bg-white p-6 rounded-lg shadow-md">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
          <input v-model="form.name" type="text" required class="w-full border-gray-300 rounded-md shadow-sm border p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
          <input v-model="form.email" type="email" required class="w-full border-gray-300 rounded-md shadow-sm border p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
          <input v-model="form.password" type="password" :required="!isEditing" class="w-full border-gray-300 rounded-md shadow-sm border p-2" />
        </div>
      </div>
      <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
          {{ isEditing ? 'Actualizar Profesor' : 'Registrar Profesor' }}
        </button>
        <button v-if="isEditing" @click="resetForm" type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition">
          Cancelar
        </button>
      </div>
    </form>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="p-4 font-semibold text-gray-700">Nombre</th>
            <th class="p-4 font-semibold text-gray-700">Correo</th>
            <th class="p-4 font-semibold text-gray-700">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="profesor in profesores" :key="profesor.id" class="hover:bg-gray-50 transition">
            <td class="p-4 text-gray-800">{{ profesor.name }}</td>
            <td class="p-4 text-gray-600">{{ profesor.email }}</td>
            <td class="p-4 flex gap-2">
              <button @click="editProfesor(profesor)" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-md text-sm font-medium transition">
                Editar
              </button>
              <button @click="deleteProfesor(profesor.id)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-sm font-medium transition">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const profesores = ref([]);
const isEditing = ref(false);
const editingId = ref(null);
const form = ref({
  name: '',
  email: '',
  password: ''
});

const fetchProfesores = async () => {
  try {
    const response = await axios.get('/api/profesores');
    profesores.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const saveProfesor = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/profesores/${editingId.value}`, form.value);
    } else {
      await axios.post('/api/profesores', form.value);
    }
    await fetchProfesores();
    resetForm();
  } catch (error) {
    console.error(error);
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
};

const deleteProfesor = async (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar a este profesor?')) {
    try {
      await axios.delete(`/api/profesores/${id}`);
      await fetchProfesores();
    } catch (error) {
      console.error(error);
    }
  }
};

const resetForm = () => {
  isEditing.value = false;
  editingId.value = null;
  form.value = { name: '', email: '', password: '' };
};

onMounted(fetchProfesores);
</script>