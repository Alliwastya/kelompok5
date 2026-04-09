import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

export default function Reports({ dailySales, monthlySales, totalRevenue, totalOrders, totalShipping, filters }) {
    const { data, setData, get } = useForm({
        start_date: filters.start_date || '',
        end_date: filters.end_date || '',
    });

    const handleFilter = (e) => {
        e.preventDefault();
        get(route('admin.reports'));
    };

    return (
        <AuthenticatedLayout>
            <Head title="Laporan Penjualan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
                        <p className="text-gray-500 mt-1">Analisis pendapatan dan penjualan Anda</p>
                    </div>

                    {/* Filter */}
                    <div className="bg-white shadow rounded-lg p-6 mb-6">
                        <form onSubmit={handleFilter} className="flex gap-4 flex-wrap items-end">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input
                                    type="date"
                                    value={data.start_date}
                                    onChange={(e) => setData('start_date', e.target.value)}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Ke Tanggal</label>
                                <input
                                    type="date"
                                    value={data.end_date}
                                    onChange={(e) => setData('end_date', e.target.value)}
                                    className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <button
                                type="submit"
                                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Filter
                            </button>
                        </form>
                    </div>

                    {/* Summary Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <dt className="text-sm font-medium text-gray-500 truncate">Total Pendapatan</dt>
                                <dd className="mt-1 text-3xl font-extrabold text-green-600">
                                    Rp {totalRevenue.toLocaleString('id-ID')}
                                </dd>
                            </div>
                        </div>
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <dt className="text-sm font-medium text-gray-500 truncate">Total Pesanan</dt>
                                <dd className="mt-1 text-3xl font-extrabold text-blue-600">
                                    {totalOrders}
                                </dd>
                            </div>
                        </div>
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <dt className="text-sm font-medium text-gray-500 truncate">Total Ongkos Kirim</dt>
                                <dd className="mt-1 text-3xl font-extrabold text-orange-600">
                                    Rp {totalShipping.toLocaleString('id-ID')}
                                </dd>
                            </div>
                        </div>
                        <div className="bg-white overflow-hidden shadow rounded-lg">
                            <div className="px-4 py-5 sm:p-6">
                                <dt className="text-sm font-medium text-gray-500 truncate">Rata-rata per Pesanan</dt>
                                <dd className="mt-1 text-3xl font-extrabold text-purple-600">
                                    {totalOrders > 0 ? `Rp ${Math.round(totalRevenue / totalOrders).toLocaleString('id-ID')}` : 'Rp 0'}
                                </dd>
                            </div>
                        </div>
                    </div>

                    {/* Daily Sales Table */}
                    <div className="bg-white shadow rounded-lg mb-6">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-bold text-gray-900">Penjualan Per Hari</h2>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pesanan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {dailySales.map((sale) => (
                                        <tr key={sale.date} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {new Date(sale.date).toLocaleDateString('id-ID')}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {sale.count}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                Rp {parseInt(sale.total).toLocaleString('id-ID')}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Monthly Sales Table */}
                    <div className="bg-white shadow rounded-lg">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-bold text-gray-900">Penjualan Per Bulan</h2>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pesanan</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {monthlySales.map((sale) => {
                                        const monthName = new Date(sale.year, sale.month - 1).toLocaleDateString('id-ID', { 
                                            year: 'numeric',
                                            month: 'long' 
                                        });
                                        return (
                                            <tr key={`${sale.year}-${sale.month}`} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {monthName}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {sale.count}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                    Rp {parseInt(sale.total).toLocaleString('id-ID')}
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
