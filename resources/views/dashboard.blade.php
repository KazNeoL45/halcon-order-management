<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalOrders }}</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalProducts }}</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock (<10)</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $lowStockProducts }}</p>
                </div>
            </div>

            <!-- User & Client Counts -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalUsers }}</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clients</h3>
                    <p class="mt-2 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalClients }}</p>
                </div>
            </div>

            <!-- Orders by Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Orders by Status</h3>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($ordersByStatus as $status)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                            <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $status->status }}</p>
                            <p class="mt-1 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $status->count }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Orders by Region -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Orders by Region</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Region</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Orders</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($ordersByRegion as $region)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $region->region ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">{{ $region->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
