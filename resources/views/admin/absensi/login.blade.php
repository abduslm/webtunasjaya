<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard Admin</title>
    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css'], ['resources/js/app.js'])
    <!-- Font Awesome CDN untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .bg-primary {
            background-color: #0d3d2e;
        }
        .text-primary {
            color: #0d3d2e;
        }
    </style>
</head>
<body class="bg-gray-50" >

    <div class="flex min-h-screen" >
        
        <!-- BAGIAN KIRI: Banner -->
        <div class="hidden lg:flex lg:w-1/2 bg-primary items-center justify-center p-12 text-white relative overflow-hidden">
            <!-- Dekorasi Bintang/Sparkle (Opsional) -->
            <div class="absolute top-1/2 right-10 opacity-20">
                <i class="fa-solid fa-sparkles text-6xl"></i>
            </div>
            
            <div class="max-w-md text-center">
                <h1 class="text-5xl font-bold leading-tight mb-6">
                    Selamat Datang di Dashboard Admin
                </h1>
                <p class="text-lg font-light opacity-80 leading-relaxed">
                    Kesejukan dan kebersihan untuk hunian impian Anda, kini hanya sejauh satu sentuhan.
                </p>
            </div>
        </div>


        <!-- BAGIAN KANAN: Form Login -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Login</h2>
                <p class="text-gray-500 mb-8">Silakan masuk ke akun Anda</p>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-center gap-3 text-red-700">
                        <i class="fas fa-exclamation-circle"></i>
                        <p class="text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input type="email" name="email" placeholder="Masukkan email anda"  required value="{{ old('email') }}"
                                class="w-full pl-11 pr-4 py-3 bg-gray-100 border border-transparent rounded-xl focus:bg-white focus:border-green-800 focus:outline-none transition duration-200">
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fa-solid fa-key text-sm"></i>
                            </span>
                            <input type="password" name="password" placeholder="Masukkan password anda" required
                                class="w-full pl-11 pr-12 py-3 bg-gray-100 border border-transparent rounded-xl focus:bg-white focus:border-green-800 focus:outline-none transition duration-200">
            
                        </div>
                    </div>
                    
                    <!-- Tombol Login -->
                    <button type="submit" 
                        class="w-full bg-primary text-white font-bold py-3 rounded-xl hover:bg-opacity-90 transition duration-300 shadow-lg shadow-green-900/20 uppercase tracking-wider">
                        Login
                    </button>
                </form>

                <!-- Divider ATAU -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-4 text-gray-500 font-medium">Atau</span>
                    </div>
                </div>

                <!-- Google Login -->
                <button class="w-full flex items-center justify-center gap-3 border border-gray-300 py-3 rounded-xl hover:bg-gray-50 transition duration-300">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo" class="w-5 h-5">
                    <span class="text-gray-700 font-medium">Masuk dengan Google</span>
                </button>

                <!-- Footer Link -->
                <p class="text-center mt-8 text-gray-600">
                    Belum punya akun? 
                    <a href="#" class="text-primary font-bold hover:underline">Daftar sekarang</a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>
