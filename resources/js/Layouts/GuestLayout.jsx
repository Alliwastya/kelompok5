import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-orange-50">
            {/* Background decorative elements */}
            <div className="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -z-10 animate-blob"></div>
            <div className="absolute bottom-0 left-0 w-96 h-96 bg-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -z-10 animate-blob animation-delay-2000"></div>
            <div className="absolute -bottom-8 right-20 w-96 h-96 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 -z-10 animate-blob animation-delay-4000"></div>

            <div className="flex min-h-screen flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
                {/* Logo Section */}
                <div className="mb-8 text-center">
                    <Link href="/" className="inline-block">
                        <div className="text-5xl font-bold text-blue-600 mb-2">🍞</div>
                        <h1 className="text-3xl font-bold text-gray-900">Admin Panel</h1>
                        <p className="text-gray-500 text-sm mt-1">Manajemen Roti Terbaik</p>
                    </Link>
                </div>

                {/* Login Card */}
                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-xl overflow-hidden">
                        {/* Header Gradient */}
                        <div className="h-2 bg-gradient-to-r from-blue-600 to-orange-500"></div>
                        
                        {/* Content */}
                        <div className="px-6 py-8 sm:px-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-1">Selamat Datang Admin</h2>
                            <p className="text-gray-600 text-sm mb-6">Masuk ke panel admin Anda</p>
                            {children}
                        </div>

                        {/* Footer */}
                        <div className="px-6 py-4 sm:px-8 bg-gray-50 border-t border-gray-200">
                            <p className="text-center text-xs text-gray-600">
                                © 2026 Admin Panel Roti. Semua hak dilindungi.
                            </p>
                        </div>
                    </div>

                    {/* Security Info */}
                    <div className="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p className="text-xs text-blue-700 text-center">
                            🔒 Halaman ini aman dan terenkripsi. Jangan bagikan kredensial Anda.
                        </p>
                    </div>
                </div>
            </div>

            {/* CSS for animations */}
            <style>{`
                @keyframes blob {
                    0%, 100% {
                        transform: translate(0, 0) scale(1);
                    }
                    33% {
                        transform: translate(30px, -50px) scale(1.1);
                    }
                    66% {
                        transform: translate(-20px, 20px) scale(0.9);
                    }
                }
                
                .animate-blob {
                    animation: blob 7s infinite;
                }
                
                .animation-delay-2000 {
                    animation-delay: 2s;
                }
                
                .animation-delay-4000 {
                    animation-delay: 4s;
                }
            `}</style>
        </div>
    );
}
