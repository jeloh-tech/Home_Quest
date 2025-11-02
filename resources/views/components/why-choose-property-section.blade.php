<section class="py-20 relative overflow-hidden bg-blue-50 dark:bg-gray-900">

    <!-- Floating geometric shapes for visual interest -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-gradient-to-r from-blue-400/20 to-purple-400/20 rounded-full blur-xl"></div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-gradient-to-r from-purple-400/20 to-pink-400/20 rounded-full blur-xl"></div>
    <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-gradient-to-r from-cyan-400/20 to-blue-400/20 rounded-full blur-xl"></div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4 leading-tight">
                Why People Choose Property
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Discover the advantages that make our platform the preferred choice for property seekers and owners alike.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Professional Puzzle Design - 3 Vertical Rectangles -->
            <div class="relative group">
                <div class="relative rounded-2xl p-2 transition-all duration-500 overflow-hidden">
                    <div class="relative w-full h-[28rem]">
                        <!-- Left Rectangle -->
                        <div class="absolute top-1/6 left-8 transform -translate-y-1/6 w-40 h-80 rounded-xl overflow-hidden hover:scale-105 transition-all duration-500 z-30">
                            <img src="{{ asset('storage/img/bahay.jpeg') }}" alt="Property 1" class="w-full h-full object-cover">
                            <!-- Puzzle piece connector -->
                            <div class="absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-lg z-10"></div>
                        </div>

                        <!-- Center Rectangle -->
                        <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/3 w-40 h-80 rounded-xl overflow-hidden hover:scale-105 transition-all duration-500 z-20">
                            <img src="{{ asset('storage/img/bahay.jpeg') }}" alt="Property 2" class="w-full h-full object-cover">
                            <!-- Puzzle piece connectors -->
                            <div class="absolute top-1/2 left-0 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-lg z-10"></div>
                            <div class="absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-lg z-10"></div>
                        </div>

                        <!-- Right Rectangle -->
                        <div class="absolute top-2/3 right-8 transform -translate-y-2/3 w-40 h-80 rounded-xl overflow-hidden hover:scale-105 transition-all duration-500 z-10">
                            <img src="{{ asset('storage/img/bahay.jpeg') }}" alt="Property 3" class="w-full h-full object-cover">
                            <!-- Puzzle piece connector -->
                            <div class="absolute top-1/2 left-0 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-gray-800 rounded-full shadow-lg z-10"></div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute top-1/2 left-20 transform -translate-y-1/2 w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                        <div class="absolute top-1/2 right-20 transform -translate-y-1/2 w-2 h-2 bg-pink-500 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
                    </div>
                </div>
            </div>

            <!-- Professional Text content on the right -->
            <div class="space-y-8">
                <div class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-300">Verified Listings</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">Every property undergoes rigorous verification to ensure authenticity, quality, and compliance with standards.</p>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">Competitive Pricing</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">Access the most competitive rental rates with complete transparency in pricing and no hidden fees.</p>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start space-x-5">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">Easy & Fast</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">Experience a streamlined application process with instant communication and rapid response times.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
