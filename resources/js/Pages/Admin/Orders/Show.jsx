import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function OrderShow({ order }) {
    const { data, setData, patch, processing } = useForm({
        status: order.status,
        notes: order.notes || '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        patch(route('admin.orders.update-status', order.id));
    };

    const statusColors = {
        pending: 'bg-gray-100 text-gray-800',
        processing: 'bg-yellow-100 text-yellow-800',
        shipped: 'bg-blue-100 text-blue-800',
        delivered: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };

    return (
        <AuthenticatedLayout>
            <Head title={`Pesanan ${order.order_number}`} />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-8 flex justify-between items-start">
                        <div>
                            <h1 className="text-3xl font-bold text-gray-900">{order.order_number}</h1>
                            <p className="text-gray-500 mt-1">
                                Dibuat: {new Date(order.created_at).toLocaleDateString('id-ID', { 
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        </div>
                        <span className={`px-4 py-2 rounded-full font-medium ${statusColors[order.status]}`}>
                            {order.status}
                        </span>
                    </div>

                    {/* Customer Info */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 className="text-lg font-bold text-gray-900 mb-4">Informasi Pelanggan</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p className="text-sm text-gray-600">Nama</p>
                                <p className="text-lg font-medium text-gray-900">{order.customer_name}</p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">Telepon</p>
                                <p className="text-lg font-medium text-gray-900">{order.customer_phone}</p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">Email</p>
                                <p className="text-lg font-medium text-gray-900">{order.customer_email || '-'}</p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">Alamat</p>
                                <p className="text-lg font-medium text-gray-900">{order.customer_address}</p>
                            </div>
                        </div>
                    </div>

                    {/* Order Items */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 className="text-lg font-bold text-gray-900 mb-4">Produk Pesanan</h2>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {order.items && order.items.map((item) => (
                                        <tr key={item.id}>
                                            <td className="px-4 py-3 text-sm text-gray-900">{item.product_name}</td>
                                            <td className="px-4 py-3 text-sm text-gray-900">Rp {parseInt(item.price).toLocaleString('id-ID')}</td>
                                            <td className="px-4 py-3 text-sm text-gray-900">{item.quantity}</td>
                                            <td className="px-4 py-3 text-sm font-medium text-gray-900">Rp {parseInt(item.subtotal).toLocaleString('id-ID')}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Totals */}
                        <div className="mt-6 flex justify-end">
                            <div className="w-64">
                                <div className="flex justify-between py-2 border-t border-b border-gray-200">
                                    <span className="font-medium text-gray-900">Total Barang</span>
                                    <span className="text-gray-900">
                                        Rp {(order.total_amount - order.shipping_cost).toLocaleString('id-ID')}
                                    </span>
                                </div>
                                <div className="flex justify-between py-2 border-b border-gray-200">
                                    <span className="font-medium text-gray-900">Ongkos Kirim</span>
                                    <span className="text-gray-900">Rp {parseInt(order.shipping_cost).toLocaleString('id-ID')}</span>
                                </div>
                                <div className="flex justify-between py-3 font-bold text-lg">
                                    <span className="text-gray-900">Total</span>
                                    <span className="text-green-600">Rp {parseInt(order.total_amount).toLocaleString('id-ID')}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Update Status */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 className="text-lg font-bold text-gray-900 mb-4">Update Status Pesanan</h2>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    rows="4"
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Tambahkan catatan tentang pesanan ini..."
                                />
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                            >
                                {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                            </button>
                        </form>
                    </div>

                    {/* Delivery Info */}
                    <div className="bg-white shadow rounded-lg p-6">
                        <h2 className="text-lg font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p className="text-sm text-gray-600">Metode Pembayaran</p>
                                <p className="text-lg font-medium text-gray-900">{order.payment_method}</p>
                            </div>
                            <div>
                                <p className="text-sm text-gray-600">Status Logistik</p>
                                <div className="mt-2 flex items-center gap-2">
                                    <span className={`w-3 h-3 rounded-full ${order.status === 'delivered' ? 'bg-green-500' : 'bg-yellow-500'}`}></span>
                                    <span className="text-lg font-medium text-gray-900">
                                        {order.status === 'delivered' ? 'Pesanan Tersampaikan' : 'Dalam Proses Pengiriman'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
