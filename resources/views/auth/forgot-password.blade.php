<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - PT. Tunas Jaya Bersinar Cemerlang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    {{-- Box Utama berbasis State Alpine.js --}}
    <div x-data="{
        step: 1, {{-- 1: Input Email, 2: Input OTP, 3: Ganti Password --}}
        email: '',
        otp: '',
        password: '',
        password_confirmation: '',
        errorMessage: '',
        successMessage: '',
        isLoading: false,

        async submitEmail() {
            this.errorMessage = '';
            this.isLoading = true;
            try {
                let res = await fetch('{{ route('forgot-password.send-otp') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ email: this.email })
                });
                let data = await res.json();
                if (res.ok) {
                    this.step = 2;
                } else {
                    this.errorMessage = data.message;
                }
            } catch(e) { this.errorMessage = 'Terjadi kesalahan sistem.'; }
            this.isLoading = false;
        },

        async submitOtp() {
            this.errorMessage = '';
            this.isLoading = true;
            try {
                let res = await fetch('{{ route('forgot-password.verify-otp') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: this.email, otp: this.otp })
                });
                let data = await res.json();
                if (res.ok) {
                    this.step = 3;
                } else {
                    this.errorMessage = data.message;
                }
            } catch(e) { this.errorMessage = 'Terjadi kesalahan verifikasi.'; }
            this.isLoading = false;
        },

        async submitNewPassword() {
            this.errorMessage = '';
            this.isLoading = true;
            try {
                let res = await fetch('{{ route('forgot-password.reset-password') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        email: this.email, 
                        otp: this.otp, 
                        password: this.password, 
                        password_confirmation: this.password_confirmation 
                    })
                });
                let data = await res.json();
                if (res.ok) {
                    this.successMessage = data.message;
                    this.step = 4; {{-- Selesai --}}
                } else {
                    this.errorMessage = data.message;
                }
            } catch(e) { this.errorMessage = 'Gagal memperbarui sandi.'; }
            this.isLoading = false;
        }
    }" class="w-full max-w-md bg-white p-8 rounded-3xl border border-gray-100 shadow-xl">

        {{-- Alir Judul --}}
        <div class="text-center mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900">Pemulihan Akun</h2>
            <p class="text-sm text-gray-400 mt-1" x-show="step === 1">Masukkan email Anda untuk menerima kode OTP.</p>
            <p class="text-sm text-gray-400 mt-1" x-show="step === 2">Masukkan 6 digit kode OTP yang dikirim ke email Anda.</p>
            <p class="text-sm text-gray-400 mt-1" x-show="step === 3">Buat kata sandi baru yang aman untuk akun Anda.</p>
        </div>

        {{-- Notifikasi Error Global --}}
        <div x-show="errorMessage" x-text="errorMessage" class="mb-4 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs font-semibold" x-cloak></div>

        <!-- TAHAP 1: INPUT EMAIL -->
        <div x-show="step === 1">
            <form @submit.prevent="submitEmail" class="space-y-4">
                <div>
                    <label class="block mb-2 text-xs font-bold uppercase text-gray-500 tracking-wider">Email Pengguna</label>
                    <input type="email" x-model="email" required placeholder="nama@perusahaan.com"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0a4d3c]/10 outline-none text-sm transition-all">
                </div>
                <button type="submit" :disabled="isLoading"
                    class="w-full py-3 bg-[#0a4d3c] text-white font-bold rounded-xl shadow-lg shadow-emerald-100 text-sm hover:bg-[#0a3a2e] transition-colors flex items-center justify-center gap-2">
                    <span x-show="!isLoading"><i class="bi bi-envelope-arrow-up"></i> Kirim Kode OTP</span>
                    <span x-show="isLoading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                </button>
            </form>
        </div>

        <!-- TAHAP 2: INPUT KODE OTP -->
        <div x-show="step === 2" x-cloak>
            <form @submit.prevent="submitOtp" class="space-y-4">
                <div>
                    <label class="block mb-2 text-xs font-bold uppercase text-gray-500 tracking-wider">Kode Keamanan OTP</label>
                    <input type="text" x-model="otp" maxlength="6" required placeholder="123456"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl tracking-[0.5em] text-center font-mono text-lg focus:ring-4 focus:ring-[#0a4d3c]/10 outline-none transition-all">
                </div>
                <button type="submit" :disabled="isLoading"
                    class="w-full py-3 bg-[#0a4d3c] text-white font-bold rounded-xl shadow-lg shadow-emerald-100 text-sm hover:bg-[#0a3a2e] transition-colors flex items-center justify-center gap-2">
                    <span x-show="!isLoading">Verifikasi OTP <i class="bi bi-arrow-right"></i></span>
                    <span x-show="isLoading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                </button>
                <button type="button" @click="step = 1" class="w-full text-center text-xs text-gray-400 hover:underline">Kembali ubah email</button>
            </form>
        </div>

        <!-- TAHAP 3: INPUT PASSWORD BARU -->
        <div x-show="step === 3" x-cloak>
            <form @submit.prevent="submitNewPassword" class="space-y-4">
                <div>
                    <label class="block mb-1 text-xs font-bold uppercase text-gray-500 tracking-wider">Kata Sandi Baru</label>
                    <input type="password" x-model="password" minlength="8" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0a4d3c]/10 outline-none text-sm transition-all">
                </div>
                <div>
                    <label class="block mb-1 text-xs font-bold uppercase text-gray-500 tracking-wider">Ulangi Kata Sandi</label>
                    <input type="password" x-model="password_confirmation" minlength="8" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-[#0a4d3c]/10 outline-none text-sm transition-all">
                </div>
                <button type="submit" :disabled="isLoading"
                    class="w-full py-3 bg-[#0a4d3c] text-white font-bold rounded-xl shadow-lg shadow-emerald-100 text-sm hover:bg-[#0a3a2e] transition-colors flex items-center justify-center gap-2">
                    <span x-show="!isLoading"><i class="bi bi-check2-circle"></i> Perbarui Kata Sandi</span>
                    <span x-show="isLoading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                </button>
            </form>
        </div>

        <!-- TAHAP 4: BERHASIL -->
        <div x-show="step === 4" class="text-center space-y-4" x-cloak>
            <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto text-3xl">
                <i class="bi bi-patch-check"></i>
            </div>
            <p class="text-sm font-medium text-gray-700" x-text="successMessage"></p>
            <a href="/login" class="block w-full py-3 bg-[#0a4d3c] text-white font-bold rounded-xl text-center text-sm hover:bg-[#0a3a2e] transition-colors">
                Masuk ke Aplikasi
            </a>
        </div>

    </div>

</body>
</html>