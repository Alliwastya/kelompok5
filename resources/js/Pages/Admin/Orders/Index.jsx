import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function OrdersIndex({ orders, filters }) {
    const { data, setData, get } = useForm({
        status: filters.status || '',
        search: filters.search || '',
    });

    const handleSearch = (e) => {
        e.preventDefault();
        get(route('admin.orders.index'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Kelola Pesanan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">Kelola Pesanan</h1>
                        <p className="text-gray-500 mt-1">Kelola semua pesanan pelanggan Anda</p>
                    </div>

                    {/* Search & Filter */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <form onSubmit={handleSearch} className="flex gap-4 flex-wrap">
                            <input
                                type="text"
                                placeholder="Cari nomor pesanan atau nama pelanggan..."
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
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button
                                type="submit"
                                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Cari
                            </button>
                            <Link
                                href={route('admin.orders.create')}
                                className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                            >
                                + Pesanan Baru
                            </Link>
                        </form>
                    </div>

                    {/* Orders Table */}
                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pesanan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {orders.data.map((order) => (
                                        <tr key={order.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                {order.order_number}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{order.customer_name}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{order.customer_phone}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                Rp {order.total_amount.toLocaleString('id-ID')}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                                                    order.status === 'delivered' ? 'bg-green-100 text-green-800' :
                                                    order.status === 'shipped' ? 'bg-blue-100 text-blue-800' :
                                                    order.status === 'processing' ? 'bg-yellow-100 text-yellow-800' :
                                                    order.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                                    'bg-gray-100 text-gray-800'
                                                }`}>
                                                    {order.status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {new Date(order.created_at).toLocaleDateString('id-ID')}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm">
                                                <Link
                                                    href={route('admin.orders.show', order.id)}
                                                    className="text-blue-600 hover:text-blue-700 font-medium"
                                                >
                                                    Detail
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
                                Showing {orders.from} to {orders.to} of {orders.total} results
                            </div>
                            <div className="flex gap-2">
                                {orders.prev_page_url && (
                                    <Link
                                        href={orders.prev_page_url}
                                        className="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100"
                                    >
                                        ← Previous
                                    </Link>
                                )}
                                {orders.next_page_url && (
                                    <Link
                                        href={orders.next_page_url}
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
