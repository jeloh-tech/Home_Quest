@extends('layouts.landlord')

@section('content')
<div class="w-full -m-10">
    <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Messages</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">Stay connected with tenants and property seekers</p>
            </div>

            <!-- Messages Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Messages List -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversations</h3>
                            <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                New Message
                            </button>
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-4">
                            <input type="text" id="search-conversations" placeholder="Search conversations..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @forelse($conversations as $conversation)
                                <a href="{{ route('landlord.messages.conversation', $conversation['other_user']->id) }}" class="conversation-link flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ $conversation['unread_count'] > 0 ? 'bg-blue-50 dark:bg-blue-900' : '' }}">
                                    <img src="{{ $conversation['other_user']->profile_photo_path ? asset('storage/' . $conversation['other_user']->profile_photo_path) : asset('images/default-avatar.png') }}" alt="{{ $conversation['other_user']->name }}" class="w-10 h-10 rounded-full">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $conversation['other_user']->name }}</p>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 conversation-time" data-user-id="{{ $conversation['other_user']->id }}">{{ $conversation['latest_message']->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate conversation-subject" data-user-id="{{ $conversation['other_user']->id }}">{{ $conversation['latest_message']->subject }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate conversation-message" data-user-id="{{ $conversation['other_user']->id }}">{{ Str::limit($conversation['latest_message']->message, 50) }}</p>
                                        @if($conversation['listing'])
                                            <p class="text-xs text-blue-600 dark:text-blue-400 truncate">{{ $conversation['listing']->title }}</p>
                                        @endif
                                    </div>
                                    @if($conversation['unread_count'] > 0)
                                        <span class="w-2 h-2 bg-blue-600 rounded-full unread-indicator" data-user-id="{{ $conversation['other_user']->id }}"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No messages</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tenants will contact you about your properties.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Message Thread -->
                <div class="lg:col-span-2">
                    <div id="message-thread" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                        <!-- Default State -->
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Select a conversation</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Choose a conversation from the list to view messages.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Quick Templates</h3>
                    <div class="space-y-2">
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                            Property available
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                            Schedule viewing
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                            Application approved
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Message Stats</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Messages</span>
                            <span class="font-medium">{{ $conversations->sum(function($conv) { return $conv['latest_message'] ? 1 : 0; }) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Unread</span>
                            <span class="font-medium text-blue-600">{{ $conversations->sum('unread_count') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Active Conversations</span>
                            <span class="font-medium">{{ $conversations->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Property Management</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage your rental properties</p>
                    <a href="{{ route('landlord.properties') }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-center block">
                        View Properties
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Landlord Messages page loaded - simplified version');

    // Simple click handler for conversation links
    document.querySelectorAll('.conversation-link').forEach(link => {
        link.addEventListener('click', function(e) {
            console.log('Conversation link clicked');
            // Remove active class from all links
            document.querySelectorAll('.conversation-link').forEach(l => l.classList.remove('bg-blue-100', 'dark:bg-blue-900'));
            // Add active class to clicked link
            this.classList.add('bg-blue-100', 'dark:bg-blue-900');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('search-conversations');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.conversation-link').forEach(link => {
                const name = link.querySelector('p.font-medium').textContent.toLowerCase();
                const message = link.querySelector('p.truncate').textContent.toLowerCase();
                if (name.includes(query) || message.includes(query)) {
                    link.style.display = 'flex';
                } else {
                    link.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
