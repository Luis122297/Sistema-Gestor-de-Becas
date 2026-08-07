<template>
  <div class="min-h-screen relative flex items-center justify-center overflow-hidden bg-[#FCE5D6] font-sans">
    <div
      class="absolute inset-0"
      style="
        background-color: #FCE5D6;
        background-image:
          linear-gradient(rgba(114, 47, 55, 0.1) 1px, transparent 1px),
          linear-gradient(90deg, rgba(114, 47, 55, 0.1) 1px, transparent 1px);
        background-size: 35px 35px;
      "
    ></div>

    <div class="absolute inset-0 bg-gradient-to-br from-[#FDF0E6]/80 via-[#FCE5D6]/90 to-[#FAD4BA]"></div>

    <div class="relative z-10 w-full max-w-md px-6">
      <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-t-8 border-[#7A2033]">
        <div class="p-8">
          
          <div class="text-center mb-8">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg border-2 border-[#722F37] overflow-hidden">
              <img src="/Uptexlogo.png" alt="Logo UPTEX" class="w-full h-full object-contain p-2">
            </div>
            
            <h2 class="text-3xl font-bold text-gray-800">
              {{ showTwoFactor ? 'Verificación 2FA' : 'Becas de descuento UPtex' }}
            </h2>

            <p class="text-gray-500 mt-2">
              {{ showTwoFactor ? 'Ingresa el código de tu aplicación' : 'Universidad Politécnica' }}
            </p>
          </div>

          <form v-if="!showTwoFactor" @submit.prevent="handleLogin" class="space-y-6">
            <div>
              <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Correo Electrónico
              </label>
              <input id="email" v-model="form.email" type="email" required placeholder="ejemplo@uptex.edu.mx" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#7A2033] focus:border-transparent transition">
            </div>

            <div>
              <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                Contraseña
              </label>
              <input id="password" v-model="form.password" type="password" required placeholder="••••••••" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#7A2033] focus:border-transparent transition">
            </div>

            <div v-if="errorMessage" class="rounded-lg border border-red-200 bg-red-50 p-3 text-center text-sm font-medium text-red-600">
              {{ errorMessage }}
            </div>

            <button type="submit" :disabled="isLoading" class="w-full rounded-lg bg-[#7A2033] py-3 font-semibold text-white transition hover:bg-[#561322] disabled:cursor-not-allowed disabled:bg-gray-400">
              {{ isLoading ? 'Validando...' : 'Ingresar al Sistema' }}
            </button>
            
            <div class="text-center mt-4">
              <a href="/register" class="text-sm text-[#7A2033] hover:underline font-semibold">¿No tienes cuenta? Regístrate aquí</a>
            </div>
          </form>

          <form v-else @submit.prevent="verifyTwoFactor" class="space-y-6">
            <div>
              <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                Código de 6 dígitos
              </label>
              <input id="code" v-model="form.code" type="text" maxlength="6" required placeholder="123456" class="w-full rounded-lg border border-gray-300 px-4 py-3 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-[#7A2033] focus:border-transparent transition">
            </div>

            <div v-if="errorMessage" class="rounded-lg border border-red-200 bg-red-50 p-3 text-center text-sm font-medium text-red-600">
              {{ errorMessage }}
            </div>

            <button type="submit" :disabled="isLoading" class="w-full rounded-lg bg-[#7A2033] py-3 font-semibold text-white transition hover:bg-[#561322] disabled:cursor-not-allowed disabled:bg-gray-400">
              {{ isLoading ? 'Verificando...' : 'Confirmar Acceso' }}
            </button>

            <div class="text-center mt-4">
              <button type="button" @click="showTwoFactor = false" class="text-sm text-gray-500 hover:underline font-semibold">
                Volver atrás
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const form = ref({
    email: '',
    password: '',
    code: ''
});

const showTwoFactor = ref(false);
const errorMessage = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post('/api/login', {
            email: form.value.email,
            password: form.value.password
        });
        
        if (response.data['2fa_required']) {
            showTwoFactor.value = true;
        } else {
            localStorage.setItem('auth_token', response.data.access_token);
            localStorage.setItem('user_role', response.data.role);
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
            router.push('/');
        }
    } catch (error) {
        errorMessage.value = 'Correo o contraseña incorrectos.';
    } finally {
        isLoading.value = false;
    }
};

const verifyTwoFactor = async () => {
    errorMessage.value = '';
    isLoading.value = true;

    try {
        const response = await axios.post('/api/verify-2fa', {
            email: form.value.email,
            code: form.value.code
        });
        
        localStorage.setItem('auth_token', response.data.access_token);
        localStorage.setItem('user_role', response.data.role);
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
        router.push('/');
    } catch (error) {
        errorMessage.value = 'Código inválido.';
    } finally {
        isLoading.value = false;
    }
};
</script>