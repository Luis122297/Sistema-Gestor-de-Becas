<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Becas</title>
    <!-- Cargamos Tailwind desde CDN para el diseño -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Configuración de los colores oficiales del Gobierno */
        .bg-guinda { background-color: #9F2241; }
        .text-guinda { color: #9F2241; }
        .border-guinda { border-color: #9F2241; }
        .hover-bg-guinda-dark:hover { background-color: #691C32; }
        
        .bg-dorado { background-color: #D4C19C; }
        .text-dorado { color: #D4C19C; }
        .border-dorado { border-color: #D4C19C; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans selection:bg-[#9F2241] selection:text-white">

    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-200">
        
        <div class="bg-guinda w-full md:w-5/12 p-10 flex flex-col justify-between items-center text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            
            <div class="relative z-10 w-full mt-8">
                <div class="w-28 h-28 border-4 border-dorado rounded-full flex items-center justify-center mx-auto mb-6 bg-white shadow-xl">
                    <span class="text-5xl"></span>
                </div>
                
                <h1 class="text-3xl font-extrabold text-white tracking-wide mb-3">Gobierno de México</h1>
                <div class="h-1 w-20 bg-dorado mx-auto mb-6 rounded-full shadow-sm"></div>
                <p class="text-dorado text-xl font-semibold tracking-wide">Sistema Integral de Becas</p>
            </div>
            
            <div class="relative z-10 text-white/80 text-sm mt-12 font-medium">
                <p>Educación pública, gratuita y de calidad para todos.</p>
            </div>
        </div>

        <!-- Panel Derecho (Formulario de Login) -->
        <div class="w-full md:w-7/12 p-8 md:p-14 relative flex flex-col justify-center">
            
            <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Bienvenido</h2>
            <p class="text-gray-500 mb-8">Ingresa tus credenciales para acceder a tu expediente.</p>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-[#9F2241] p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-[#9F2241] font-bold">Error</span>
                        </div>
                        <div class="ml-3">
                            <ul class="text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ url('/api/login') }}" method="POST" class="space-y-6">
                <!-- Protección CSRF obligatoria para la lista de cotejo -->
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Correo Institucional</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#9F2241] focus:border-[#9F2241] transition-colors bg-gray-50 text-gray-900 shadow-sm"
                        placeholder="ejemplo@alumno.edu.mx">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#9F2241] focus:border-[#9F2241] transition-colors bg-gray-50 text-gray-900 shadow-sm"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#9F2241] border-gray-300 rounded focus:ring-[#9F2241]">
                        <span class="ml-2 text-sm font-medium text-gray-600">Recordar mis datos</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-guinda hover:underline transition-colors">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="mt-6 w-full bg-guinda hover-bg-guinda-dark text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 flex justify-center items-center">
                    Ingresar al Sistema
                </button>
            </form>

            <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600 font-medium mb-4">Eres nuevo y ya tienes una cuenta?</p>
                <a href="{{ url('/register') }}" class="inline-block w-full border-2 border-guinda text-guinda font-bold py-3 px-4 rounded-xl hover:bg-guinda hover:text-white transition-colors">
                    Crear una cuenta nueva
                </a>
            </div>
            
            <div class="text-center mt-8">
                <span class="inline-flex items-center justify-center px-4 py-1.5 bg-gray-100 text-gray-500 text-xs font-bold rounded-full border border-gray-200">
                </span>
            </div>
        </div>
    </div>
</body>
</html>
