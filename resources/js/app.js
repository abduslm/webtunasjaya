import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Membuat status mobile menjadi global dan reaktif
const mobileState = Alpine.reactive({
    isMobile: window.innerWidth < 768,
});

// Update otomatis saat layar di-resize
window.addEventListener('resize', () => {
    mobileState.isMobile = window.innerWidth < 768;
});

// Daftarkan ke Alpine agar bisa dipanggil dengan $store.global.isMobile
document.addEventListener('alpine:init', () => {
    Alpine.store('global', {
        get isMobile() { return mobileState.isMobile }
    });

    Alpine.data('sidebarLogic', () => ({
        open: !mobileState.isMobile, // Sidebar otomatis tertutup di HP
        init() {
            // Jika butuh logika tambahan saat inisialisasi
        }
    }));
});

Alpine.start();