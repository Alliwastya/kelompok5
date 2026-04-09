import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Login Admin - Roti" />

            {status && (
                <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-green-700">
                    ✓ {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                {/* Email Field */}
                <div>
                    <InputLabel htmlFor="email" value="📧 Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="admin@roti.com"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2 text-sm" />
                </div>

                {/* Password Field */}
                <div>
                    <InputLabel htmlFor="password" value="🔑 Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="••••••••"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2 text-sm" />
                </div>

                {/* Remember Me & Forgot Password */}
                <div className="flex items-center justify-between">
                    <label className="flex items-center cursor-pointer">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                        />
                        <span className="ms-2 text-sm text-gray-600 hover:text-gray-900">
                            Ingat saya
                        </span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-blue-600 hover:text-blue-700 font-medium transition"
                        >
                            Lupa password?
                        </Link>
                    )}
                </div>

                {/* Submit Button */}
                <PrimaryButton 
                    className="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed mt-6" 
                    disabled={processing}
                >
                    {processing ? '⏳ Sedang masuk...' : '✓ Masuk ke Admin Panel'}
                </PrimaryButton>
            </form>

            {/* Additional Info */}
            <div className="mt-6 pt-6 border-t border-gray-200">
                <p className="text-center text-xs text-gray-600">
                    Butuh bantuan? Hubungi administrator.
                </p>
            </div>
        </GuestLayout>
    );
}
