import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function MessageShow({ thread, messages }) {
    const [autoRefresh, setAutoRefresh] = useState(true);
    const { data, setData, post, processing } = useForm({
        message: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.messages.reply', thread.id), {
            onSuccess: () => {
                setData('message', '');
                // Refresh page after sending
                setTimeout(() => window.location.reload(), 500);
            }
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Chat dengan ${thread.name}`} />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">💬 {thread.name}</h1>
                        <p className="text-gray-500 mt-1">{thread.phone}</p>
                    </div>

                    {/* Chat Thread */}
                    <div className="bg-white shadow rounded-lg overflow-hidden flex flex-col" style={{ height: '600px' }}>
                        {/* Messages */}
                        <div className="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                            {messages.length === 0 ? (
                                <div className="text-center py-12 text-gray-500">
                                    <p className="text-lg">Belum ada pesan dalam thread ini</p>
                                </div>
                            ) : (
                                messages.map((msg) => (
                                    <div 
                                        key={msg.id} 
                                        className={`flex ${msg.sender_type === 'user' ? 'justify-start' : 'justify-end'}`}
                                    >
                                        <div 
                                            className={`max-w-xs rounded-lg p-4 ${
                                                msg.sender_type === 'user' 
                                                    ? 'bg-blue-100 text-blue-900' 
                                                    : 'bg-green-100 text-green-900'
                                            }`}
                                        >
                                            {msg.sender_type === 'admin' && (
                                                <p className="text-xs font-semibold mb-1">Admin</p>
                                            )}
                                            <p className="text-sm whitespace-pre-wrap break-words">{msg.message}</p>
                                            <p className="text-xs opacity-70 mt-2">
                                                {new Date(msg.created_at).toLocaleTimeString('id-ID', { 
                                                    hour: '2-digit', 
                                                    minute: '2-digit' 
                                                })}
                                            </p>
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>

                        {/* Reply Form */}
                        <div className="border-t border-gray-200 p-6 bg-white">
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <div className="flex gap-2">
                                    <textarea
                                        value={data.message}
                                        onChange={(e) => setData('message', e.target.value)}
                                        required
                                        rows="3"
                                        placeholder="Ketik pesan Anda..."
                                        className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                                    />
                                </div>
                                <div className="flex gap-2">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 font-medium"
                                    >
                                        {processing ? '⏳ Mengirim...' : '✉️ Kirim Pesan'}
                                    </button>
                                    <a
                                        href={`https://wa.me/${thread.phone.replace(/^0/, '62')}?text=${encodeURIComponent(data.message || 'Halo ' + thread.name)}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-center font-medium"
                                    >
                                        💬 WhatsApp
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Thread Info */}
                    <div className="mt-6 bg-white shadow rounded-lg p-6">
                        <h2 className="text-lg font-bold text-gray-900 mb-4">Informasi Thread</h2>
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <p className="text-sm text-gray-600">Status</p>
                                <p className="text-lg font-semibold text-gray-900">
                                    {thread.status === 'closed' ? '🔒 Tertutup' : '🟢 Terbuka'}
                                </p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">Pesan Terakhir</p>
                                <p className="text-lg font-semibold text-gray-900">
                                    {thread.last_message_at ? new Date(thread.last_message_at).toLocaleDateString('id-ID') : 'Belum ada'}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
