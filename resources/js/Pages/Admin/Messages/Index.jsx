import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function MessagesIndex({ threads, filters }) {
    const { data, setData, get } = useForm({
        status: filters.status || '',
        search: filters.search || '',
    });

    const handleSearch = (e) => {
        e.preventDefault();
        get(route('admin.messages.index'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Chat dengan Pelanggan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">💬 Chat dengan Pelanggan</h1>
                        <p className="text-gray-500 mt-1">Kelola percakapan dengan pelanggan</p>
                    </div>

                    {/* Search & Filter */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <form onSubmit={handleSearch} className="flex gap-4 flex-wrap">
                            <input
                                type="text"
                                placeholder="Cari nama atau nomor telepon..."
                                value={data.search}
                                onChange={(e) => setData('search', e.target.value)}
                                className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <select
                                value={data.status}
                                onChange={(e) => setData('status', e.target.value)}
                                className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="open">Terbuka</option>
                                <option value="closed">Tertutup</option>
                            </select>
                            <button
                                type="submit"
                                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Cari
                            </button>
                        </form>
                    </div>

                    {/* Messages Table */}
                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pesan Terakhir</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {threads.data.map((thread) => (
                                        <tr key={thread.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{thread.name}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{thread.phone}</td>
                                            <td className="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">
                                                {thread.latest_message?.message || '(Tidak ada pesan)'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                                                    thread.status === 'closed' ? 'bg-gray-100 text-gray-800' :
                                                    'bg-green-100 text-green-800'
                                                }`}>
                                                    {thread.status === 'closed' ? 'Tertutup' : 'Terbuka'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {thread.last_message_at ? new Date(thread.last_message_at).toLocaleDateString('id-ID') : '-'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <Link
                                                    href={route('admin.messages.show', thread.id)}
                                                    className="text-blue-600 hover:text-blue-700 font-medium"
                                                >
                                                    Buka Chat
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        <div className="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                            <div className="text-sm text-gray-600">
                                Showing {threads.from} to {threads.to} of {threads.total} results
                            </div>
                            <div className="flex gap-2">
                                {threads.prev_page_url && (
                                    <Link
                                        href={threads.prev_page_url}
                                        className="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100"
                                    >
                                        ← Previous
                                    </Link>
                                )}
                                {threads.next_page_url && (
                                    <Link
                                        href={threads.next_page_url}
                                        className="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100"
                                    >
                                        Next →
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
