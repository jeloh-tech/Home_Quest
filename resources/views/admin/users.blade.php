    @extends('layouts.app')

    @section('content')
<div class="flex min-h-screen bg-white">
        <!-- Include the separate sidebar -->
        @include('admin.sidebar')

    <div class="flex-1 min-h-screen bg-white ml-64">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
               

                <!-- Removed empty search and filter form container -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Landlords Table -->
                            <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 p-8 overflow-x-auto hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/40 to-indigo-50/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-2xl font-bold text-gray-900">Landlords</h2>
                                        <span class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 text-sm font-semibold px-4 py-2 rounded-full border-2 border-blue-300 shadow-sm">
                                            {{ $landlords->total() }} total
                                        </span>
                                    </div>

                                    <!-- Search input for landlords table -->
                                    <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
                                        <input type="text" name="landlord_table_search" id="landlord_table_search" value="{{ request('landlord_table_search') }}"
                                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300"
                                            placeholder="Search landlords by name or email...">
                                    </form>

                            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                                    <tr>
                                        <th class="px-6 py-5 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider rounded-tl-lg border border-blue-200">
                                            <a href="{{ request()->fullUrlWithQuery(['landlord_sort' => 'first_name', 'landlord_direction' => request('landlord_sort') === 'first_name' && request('landlord_direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-blue-900 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Name
                                                @if(request('landlord_sort') === 'first_name')
                                                    <span class="ml-2">{{ request('landlord_direction') === 'asc' ? '↑' : '↓' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-5 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider hidden lg:table-cell border border-blue-200">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Joined
                                        </th>
                                        <th class="px-6 py-5 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider border border-blue-200">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Status
                                        </th>
                                        <th class="px-6 py-5 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider rounded-tr-lg border border-blue-200">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                            </svg>
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($landlords as $landlord)
                                        <tr class="hover:bg-gray-50 transition-all duration-300 hover:shadow-sm">
                                            <td class="px-4 py-4 whitespace-nowrap border border-blue-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center shadow-lg overflow-hidden">
                                                            @if($landlord->profile_photo_path)
                                                                <img src="{{ asset('storage/' . $landlord->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                                                            @else
                                                                <span class="text-xs font-bold text-white">{{ substr($landlord->first_name, 0, 1) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $landlord->first_name }} {{ $landlord->last_name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $landlord->email }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap hidden lg:table-cell border border-blue-200">
                                                <span class="text-sm text-gray-900">{{ $landlord->created_at->format('M j, Y') }}</span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap border border-blue-200">
                                                @if($landlord->isActive())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-500 text-white border border-gray-500 shadow-sm">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 shadow-sm">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium border border-blue-200">
                                                <div class="flex space-x-2">
                                                    @if($landlord->verification_status !== 'banned')
                                                        <form action="{{ route('admin.landlords.ban', $landlord) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-red-500 text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500/30 rounded-md text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                                    onclick="return confirm('Are you sure you want to ban this landlord?')">
                                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                                </svg>
                                                                Suspend
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.landlords.unban', $landlord) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/30 rounded-md text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                                    onclick="return confirm('Are you sure you want to unban this landlord?')">
                                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Reinstate
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-6">
                                {{ $landlords->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>

                            <!-- Tenants Table -->
                            <div class="bg-white rounded-2xl shadow-xl border-2 border-gray-100 p-8 overflow-x-auto hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-50/40 to-emerald-50/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-2xl font-bold text-gray-900">Tenants</h2>
                                        <span class="bg-gradient-to-r from-green-100 to-green-200 text-green-800 text-sm font-semibold px-4 py-2 rounded-full border-2 border-green-300 shadow-sm">
                                            {{ $tenants->total() }} total
                                        </span>
                                    </div>
                                    <form method="GET" action="{{ route('admin.users') }}" class="mb-4">
                                        <input type="text" name="tenant_search" id="tenant_search" value="{{ request('tenant_search') }}"
                                            class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300"
                                            placeholder="Search tenants by name or email...">
                                    </form>
                                    <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                                        <thead class="bg-gradient-to-r from-green-50 to-green-100">
                                            <tr>
                                                <th class="px-6 py-5 text-left text-xs font-semibold text-green-700 uppercase tracking-wider rounded-tl-lg border border-green-200">
                                                    <a href="{{ request()->fullUrlWithQuery(['tenant_sort' => 'first_name', 'tenant_direction' => request('tenant_sort') === 'first_name' && request('tenant_direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-green-900 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        Name
                                                        @if(request('tenant_sort') === 'first_name')
                                                            <span class="ml-2">{{ request('tenant_direction') === 'asc' ? '↑' : '↓' }}</span>
                                                        @endif
                                                    </a>
                                                </th>

                                                <th class="px-6 py-5 text-left text-xs font-semibold text-green-700 uppercase tracking-wider hidden lg:table-cell border border-green-200">
                                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Joined
                                                </th>
                                                <th class="px-6 py-5 text-left text-xs font-semibold text-green-700 uppercase tracking-wider border border-green-200">
                                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Status
                                                </th>
                                                <th class="px-6 py-5 text-left text-xs font-semibold text-green-700 uppercase tracking-wider rounded-tr-lg border border-green-200">
                                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                                    </svg>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($tenants as $tenant)
                                        <tr class="hover:bg-gray-50 transition-all duration-300 hover:shadow-sm">
                                            <td class="px-4 py-4 whitespace-nowrap border border-green-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center shadow-lg overflow-hidden">
                                                            @if($tenant->profile_photo_path)
                                                                <img src="{{ asset('storage/' . $tenant->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                                                            @else
                                                                <span class="text-xs font-bold text-white">{{ substr($tenant->first_name, 0, 1) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $tenant->first_name }} {{ $tenant->last_name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $tenant->email }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 whitespace-nowrap hidden lg:table-cell border border-green-200">
                                                <span class="text-sm text-gray-900">{{ $tenant->created_at->format('M j, Y') }}</span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap border border-green-200">
                                                @if($tenant->isActive())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-500 text-white border border-gray-500 shadow-sm">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 shadow-sm">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium border border-green-200">
                                                <div class="flex space-x-2">
                                                    @if($tenant->verification_status !== 'banned')
                                                        <form action="{{ route('admin.tenants.ban', $tenant) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-red-500 text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500/30 rounded-md text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                                    onclick="return confirm('Are you sure you want to ban this tenant?')">
                                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                                </svg>
                                                                Suspend
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.tenants.unban', $tenant) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/30 rounded-md text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                                    onclick="return confirm('Are you sure you want to unban this tenant?')">
                                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Reinstate
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-6">
                                {{ $tenants->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Admins Table -->
                <div class="mt-8 bg-white rounded-2xl shadow-xl border-2 border-gray-100 p-8 overflow-x-auto hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50/40 to-pink-50/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900">Admins</h2>
                            <div class="flex items-center space-x-3">
                                @if(strtolower(auth()->user()->email) === 'main.garalde@gmail.com')
                                <a href="{{ route('admin.admins.create') }}"
                                   class="inline-flex items-center px-4 py-2 border-2 border-purple-300 text-purple-700 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 focus:outline-none focus:ring-4 focus:ring-purple-500/30 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Account
                                </a>
                                @endif
                                <span class="bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 text-sm font-semibold px-4 py-2 rounded-full border-2 border-purple-300 shadow-sm">
                                    {{ $admins->total() }} total
                                </span>
                            </div>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                                <tr>
                                    <th class="px-6 py-5 text-left text-xs font-semibold text-purple-700 uppercase tracking-wider rounded-tl-lg border-r border-purple-200">
                                        <a href="{{ request()->fullUrlWithQuery(['admin_sort' => 'first_name', 'admin_direction' => request('admin_sort') === 'first_name' && request('admin_direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-purple-900 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Name
                                            @if(request('admin_sort') === 'first_name')
                                                <span class="ml-2">{{ request('admin_direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>

                                    <th class="px-6 py-5 text-left text-xs font-semibold text-purple-700 uppercase tracking-wider border-r border-purple-200">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status
                                    </th>
                                    <th class="px-6 py-5 text-left text-xs font-semibold text-purple-700 uppercase tracking-wider rounded-tr-lg">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($admins as $admin)
                                    <tr class="hover:bg-gray-50 transition-all duration-300 hover:shadow-sm">
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-purple-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center shadow-lg">
                                                            <span class="text-sm font-bold text-white">{{ substr($admin->first_name, 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                                                        @if(strtolower($admin->email) !== 'main.garalde@gmail.com')
                                                            <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                        </td>

                                    <td class="px-6 py-4 whitespace-nowrap border-r border-purple-200">
                                        @if($admin->id === auth()->id())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border-2 border-purple-300 shadow-sm">
                                                Admin
                                            </span>
                                        @elseif($admin->isActive())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border-2 border-green-300 shadow-sm">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-2 border-red-300 shadow-sm">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                                class="inline-flex items-center px-4 py-2 border-2 border-indigo-300 text-indigo-700 bg-gradient-to-r from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 rounded-lg text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                                @if(strtolower(auth()->user()->email) === 'main.garalde@gmail.com' && $admin->id !== auth()->id() && strtolower($admin->email) !== 'main.garalde@gmail.com')
                                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center px-4 py-2 border-2 border-red-300 text-red-700 bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 focus:outline-none focus:ring-4 focus:ring-red-500/30 rounded-lg text-xs font-semibold transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                                onclick="return confirm('Are you sure you want to delete this admin?')">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $admins->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when search inputs change
        const landlordSearchInput = document.getElementById('landlord_table_search');
        const tenantSearchInput = document.getElementById('tenant_search');

        // Debounce landlord search
        let landlordSearchTimeout;
        if (landlordSearchInput) {
            landlordSearchInput.addEventListener('input', function() {
                clearTimeout(landlordSearchTimeout);
                landlordSearchTimeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 500);
            });
        }

        // Debounce tenant search
        let tenantSearchTimeout;
        if (tenantSearchInput) {
            tenantSearchInput.addEventListener('input', function() {
                clearTimeout(tenantSearchTimeout);
                tenantSearchTimeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 500);
            });
        }
    });
    </script>
    @endsection
