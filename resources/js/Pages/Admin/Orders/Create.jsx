import React, { useState } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

const products = [
    { id: 1, name: 'Roti Tawar Premium', price: 25000 },
    { id: 2, name: 'Croissant Butter', price: 15000 },
    { id: 3, name: 'Roti Cokelat', price: 12000 },
    { id: 4, name: 'Baguette Tradisional', price: 18000 },
    { id: 5, name: 'Roti Keju', price: 14000 },
    { id: 6, name: 'Donat Premium', price: 10000 },
    { id: 7, name: 'Roti Gandum', price: 22000 },
    { id: 8, name: 'Pretzel', price: 16000 },
    { id: 9, name: 'Roti Kelapa', price: 13000 },
    { id: 10, name: 'Cinnamon Roll', price: 17000 },
    { id: 11, name: 'Roti Pisang', price: 11000 },
    { id: 12, name: 'Sourdough', price: 28000 },
];

export default function CreateOrder() {
    const { data, setData, post, processing } = useForm({
        customer_name: '',
        customer_phone: '',
        customer_email: '',
        customer_address: '',
        payment_method: '',
        items: [],
    });

    const [newItem, setNewItem] = useState({
        product_name: '',
        price: 0,
        quantity: 1,
    });

    const addItem = () => {
        if (newItem.product_name && newItem.quantity > 0) {
            const updatedItems = [...data.items, newItem];
            setData('items', updatedItems);
            setNewItem({ product_name: '', price: 0, quantity: 1 });
        }
    };

    const removeItem = (index) => {
        const updatedItems = data.items.filter((_, i) => i !== index);
        setData('items', updatedItems);
    };

    const selectProduct = (product) => {
        setNewItem({
            product_name: product.name,
            price: product.price,
            quantity: 1,
        });
    };

    const totalAmount = data.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (data.customer_name && data.customer_phone && data.customer_address && data.payment_method && data.items.length > 0) {
            post(route('admin.orders.store'));
        } else {
            alert('Lengkapi semua data pesanan terlebih dahulu');
        }
    };

    return (
        <AuthenticatedLayout>
            <Head title="Buat Pesanan Baru" />

            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">Buat Pesanan Baru</h1>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Customer Info */}
                        <div className="bg-white shadow rounded-lg p-6">
                            <h2 className="text-lg font-bold text-gray-900 mb-4">Informasi Pelanggan</h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan *</label>
                                    <input
                                        type="text"
                                        value={data.customer_name}
                                        onChange={(e) => setData('customer_name', e.target.value)}
                                        required
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Masukkan nama pelanggan"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                                    <input
                                        type="tel"
                                        value={data.customer_phone}
                                        onChange={(e) => setData('customer_phone', e.target.value)}
                                        required
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Contoh: 08123456789"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        type="email"
                                        value={data.customer_email}
                                        onChange={(e) => setData('customer_email', e.target.value)}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="email@contoh.com"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman *</label>
                                    <input
                                        type="text"
                                        value={data.customer_address}
                                        onChange={(e) => setData('customer_address', e.target.value)}
                                        required
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Masukkan alamat lengkap"
                                    />
                                </div>
                            </div>
                        </div>

                        {/* Add Items */}
                        <div className="bg-white shadow rounded-lg p-6">
                            <h2 className="text-lg font-bold text-gray-900 mb-4">Tambah Produk</h2>
                            
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">Pilih Produk</label>
                                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                    {products.map((product) => (
                                        <button
                                            key={product.id}
                                            type="button"
                                            onClick={() => selectProduct(product)}
                                            className="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition"
                                        >
                                            {product.name}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                                    <input
                                        type="text"
                                        value={newItem.product_name}
                                        onChange={(e) => setNewItem({...newItem, product_name: e.target.value})}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Nama produk"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                    <input
                                        type="number"
                                        value={newItem.price}
                                        onChange={(e) => setNewItem({...newItem, price: parseInt(e.target.value) || 0})}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input
                                        type="number"
                                        min="1"
                                        value={newItem.quantity}
                                        onChange={(e) => setNewItem({...newItem, quantity: parseInt(e.target.value) || 1})}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                                <button
                                    type="button"
                                    onClick={addItem}
                                    className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                                >
                                    + Tambah
                                </button>
                            </div>
                        </div>

                        {/* Items List */}
                        {data.items.length > 0 && (
                            <div className="bg-white shadow rounded-lg p-6">
                                <h2 className="text-lg font-bold text-gray-900 mb-4">Daftar Produk</h2>
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                                <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                                <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                                <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                                <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-200">
                                            {data.items.map((item, index) => (
                                                <tr key={index}>
                                                    <td className="px-4 py-3 text-sm text-gray-900">{item.product_name}</td>
                                                    <td className="px-4 py-3 text-sm text-gray-900">Rp {item.price.toLocaleString('id-ID')}</td>
                                                    <td className="px-4 py-3 text-sm text-gray-900">{item.quantity}</td>
                                                    <td className="px-4 py-3 text-sm font-medium text-gray-900">
                                                        Rp {(item.price * item.quantity).toLocaleString('id-ID')}
                                                    </td>
                                                    <td className="px-4 py-3 text-sm">
                                                        <button
                                                            type="button"
                                                            onClick={() => removeItem(index)}
                                                            className="text-red-600 hover:text-red-700"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="mt-4 text-right">
                                    <p className="text-lg font-bold text-gray-900">
                                        Total: Rp {totalAmount.toLocaleString('id-ID')}
                                    </p>
                                </div>
                            </div>
                        )}

                        {/* Payment Method */}
                        <div className="bg-white shadow rounded-lg p-6">
                            <label className="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                            <select
                                value={data.payment_method}
                                onChange={(e) => setData('payment_method', e.target.value)}
                                required
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Pilih metode pembayaran</option>
                                <option value="Transfer Bank (BCA)">Transfer Bank (BCA)</option>
                                <option value="Transfer Bank (Mandiri)">Transfer Bank (Mandiri)</option>
                                <option value="Transfer Bank (BNI)">Transfer Bank (BNI)</option>
                                <option value="E-Wallet (GoPay)">E-Wallet (GoPay)</option>
                                <option value="E-Wallet (OVO)">E-Wallet (OVO)</option>
                                <option value="E-Wallet (Dana)">E-Wallet (Dana)</option>
                                <option value="COD (Cash on Delivery)">COD (Cash on Delivery)</option>
                            </select>
                        </div>

                        {/* Submit */}
                        <div className="flex gap-4">
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                            >
                                {processing ? 'Membuat Pesanan...' : 'Buat Pesanan'}
                            </button>
                            <a
                                href={route('admin.orders.index')}
                                className="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-center hover:bg-gray-100"
                            >
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
