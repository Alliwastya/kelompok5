import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard({ 
    totalRevenue, 
    monthlyRevenue, 
    totalOrders, 
    totalMessages,
    salesChart,
    recentOrders,
    recentMessages 
}) {
    const [timeRange, setTimeRange] = useState('month');
    return (
        <AuthenticatedLayout>
            <Head title="Admin Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                        <p className="text-gray-500 mt-1">Welcome back! Here's your business overview.</p>
                    </div>

                    {/* Stats Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        {/* Today's Revenue */}
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-1">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Pendapatan Hari Ini</dt>
                                        <dd className="mt-1 text-3xl font-extrabold text-gray-900">
                                            Rp {totalRevenue.toLocaleString('id-ID')}
                                        </dd>
                                    </div>
                                    <div className="text-4xl text-blue-500">💰</div>
                                </div>
                            </div>
                        </div>

                        {/* Monthly Revenue */}
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-1">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Pendapatan Bulan Ini</dt>
                                        <dd className="mt-1 text-3xl font-extrabold text-gray-900">
                                            Rp {monthlyRevenue.toLocaleString('id-ID')}
                                        </dd>
                                    </div>
                                    <div className="text-4xl text-green-500">📊</div>
                                </div>
                            </div>
                        </div>

                        {/* Today's Orders */}
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-1">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Pesanan Hari Ini</dt>
                                        <dd className="mt-1 text-3xl font-extrabold text-gray-900">
                                            {totalOrders}
                                        </dd>
                                    </div>
                                    <div className="text-4xl text-orange-500">📦</div>
                                </div>
                            </div>
                        </div>

                        {/* New Messages */}
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <div className="flex items-center">
                                    <div className="flex-1">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Pesan Baru</dt>
                                        <dd className="mt-1 text-3xl font-extrabold text-gray-900">
                                            {totalMessages}
                                        </dd>
                                    </div>
                                    <div className="text-4xl text-purple-500">💬</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Action Buttons */}
                    <div className="mb-8 flex gap-4">
                        <Link
                            href={route('admin.orders.create')}
                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            + Buat Pesanan Baru
                        </Link>
                        <Link
                            href={route('admin.reports')}
                            className="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            📈 Lihat Laporan
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Sales Chart */}
                        <div className="lg:col-span-2 bg-white overflow-hidden shadow rounded-lg p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h2 className="text-lg font-bold text-gray-900">Penjualan Bulan Ini</h2>
                                <div className="flex gap-2">
                                    <button 
                                        onClick={() => setTimeRange('week')}
                                        className={`px-3 py-1 rounded text-sm ${timeRange === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'}`}
                                    >
                                        Minggu
                                    </button>
                                    <button 
                                        onClick={() => setTimeRange('month')}
                                        className={`px-3 py-1 rounded text-sm ${timeRange === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'}`}
                                    >
                                        Bulan
                                    </button>
                                </div>
                            </div>
                            <div className="h-64">
                                <div className="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded">
                                    <div className="text-center">
                                        <svg className="w-12 h-12 text-blue-500 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4a1 1 0 011-1h16a1 1 0 011 1v2.757a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.757a1 1 0 00-.293-.707L3.293 7.464A1 1 0 013 6.757V4z" />
                                        </svg>
                                        <p className="text-gray-500 mb-2 font-medium">Grafik Penjualan</p>
                                        <p className="text-sm text-gray-400">Total data: <span className="font-semibold text-gray-600">{salesChart.length} hari</span></p>
                                        <div className="mt-4 space-y-1 text-xs text-gray-500">
                                            <p>💡 Chart visualization akan ditampilkan di sini</p>
                                            <p>Install Chart.js untuk visualisasi yang lebih baik</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/* Summary Stats */}
                            <div className="mt-4 grid grid-cols-3 gap-4 pt-4 border-t">
                                <div className="text-center">
                                    <p className="text-gray-500 text-sm">Total Penjualan</p>
                                    <p className="text-lg font-bold text-gray-900">Rp {monthlyRevenue.toLocaleString('id-ID')}</p>
                                </div>
                                <div className="text-center">
                                    <p className="text-gray-500 text-sm">Total Pesanan</p>
                                    <p className="text-lg font-bold text-gray-900">{salesChart.reduce((acc, item) => acc + item.count, 0)}</p>
                                </div>
                                <div className="text-center">
                                    <p className="text-gray-500 text-sm">Rata-rata per Hari</p>
                                    <p className="text-lg font-bold text-gray-900">Rp {Math.floor(monthlyRevenue / (salesChart.length || 1)).toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>

                        {/* Quick Links */}
                        <div className="bg-white overflow-hidden shadow rounded-lg p-6">
                            <h2 className="text-lg font-bold text-gray-900 mb-4">Menu Cepat</h2>
                            <div className="space-y-2">
                                <Link
                                    href={route('admin.orders.index')}
                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded"
                                >
                                    📋 Kelola Pesanan
                                </Link>
                                <Link
                                    href={route('admin.messages.index')}
                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded"
                                >
                                    💬 Kelola Pesan
                                </Link>
                                <Link
                                    href={route('admin.reports')}
                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded"
                                >
                                    📊 Laporan Penjualan
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Recent Orders */}
                    <div className="mt-6 bg-white overflow-hidden shadow rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <div className="flex justify-between items-center">
                                <h2 className="text-lg font-bold text-gray-900">Pesanan Terbaru</h2>
                                <Link
                                    href={route('admin.orders.index')}
                                    className="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1"
                                >
                                    Lihat Semua <span>→</span>
                                </Link>
                            </div>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                    {recentOrders && recentOrders.length > 0 ? (
                                        recentOrders.map((order) => (
                                            <tr key={order.id} className="hover:bg-gray-50 transition">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{order.order_number}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{order.customer_name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {order.total_amount.toLocaleString('id-ID')}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                                        order.status === 'delivered' ? 'bg-green-100 text-green-800' :
                                                        order.status === 'shipped' ? 'bg-blue-100 text-blue-800' :
                                                        order.status === 'processing' ? 'bg-yellow-100 text-yellow-800' :
                                                        order.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                                        'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {order.status === 'delivered' && '✓ Terkirim'}
                                                        {order.status === 'shipped' && '🚚 Dikirim'}
                                                        {order.status === 'processing' && '⏳ Diproses'}
                                                        {order.status === 'cancelled' && '✕ Dibatalkan'}
                                                        {order.status === 'pending' && '⏳ Menunggu'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                    <Link
                                                        href={route('admin.orders.show', order.id)}
                                                        className="text-blue-600 hover:text-blue-800 font-medium transition"
                                                    >
                                                        Detail →
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-4 text-center text-gray-500">
                                                Belum ada pesanan hari ini
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Recent Messages */}
                    <div className="mt-6 bg-white overflow-hidden shadow rounded-lg">
                        <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <div className="flex justify-between items-center">
                                <h2 className="text-lg font-bold text-gray-900">Pesan Terbaru</h2>
                                <Link
                                    href={route('admin.messages.index')}
                                    className="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1"
                                >
                                    Lihat Semua <span>→</span>
                                </Link>
                            </div>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                    {recentMessages && recentMessages.length > 0 ? (
                                        recentMessages.map((msg) => (
                                            <tr key={msg.id} className="hover:bg-gray-50 transition">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{msg.name}</td>
                                                <td className="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                                    <div className="truncate">
                                                        {msg.message}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                                        msg.status === 'replied' ? 'bg-green-100 text-green-800' :
                                                        msg.status === 'read' ? 'bg-blue-100 text-blue-800' :
                                                        'bg-yellow-100 text-yellow-800'
                                                    }`}>
                                                        {msg.status === 'replied' && '✓ Dibalas'}
                                                        {msg.status === 'read' && '👀 Dibaca'}
                                                        {msg.status === 'unread' && '🔔 Baru'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                    <Link
                                                        href={route('admin.messages.show', msg.id)}
                                                        className="text-blue-600 hover:text-blue-800 font-medium transition"
                                                    >
                                                        Balas →
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="4" className="px-6 py-4 text-center text-gray-500">
                                                Tidak ada pesan baru
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
