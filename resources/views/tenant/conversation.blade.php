@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
    <!-- Fixed Sidebar on Left -->
    <aside class="w-64 flex-shrink-0 h-full overflow-y-auto bg-white dark:bg-gray-800 shadow-xl border-r border-gray-200 dark:border-gray-700">
        @include('tenant.sidebar')
    </aside>

    <!-- Scrollable Main Content -->
    <main class="flex-1 overflow-y-auto">
        <div class="min-h-screen">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-6 py-4">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('tenant.messages') }}" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back to Messages
                            </a>
                            <div class="flex-1 text-center">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">No. {{ $otherUser->phone }}</h1>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Online</span>
                            </div>
                        </div>
                </div>
            </div>

            <!-- Conversation Layout -->
            <div class="max-w-7xl mx-auto px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-140px)]">
                    <!-- Messages List Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversations</h3>
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <a href="{{ route('tenant.messages') }}" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">All Messages</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">View all conversations</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Message Thread -->
                    <div class="lg:col-span-3">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg h-full flex flex-col">
                            <!-- Message Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-t-2xl">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $otherUser->profile_photo_path ? asset('storage/' . $otherUser->profile_photo_path) : asset('images/default-avatar.png') }}" alt="{{ $otherUser->name }}" class="w-14 h-14 rounded-full object-cover ring-4 ring-white dark:ring-gray-800">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $otherUser->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $otherUser->role === 'landlord' ? 'Property Manager - Online' : 'Tenant - Online' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages -->
                            <div class="p-6 space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                                @forelse($messages as $message)
                                    @if($message->sender_id === auth()->id())
                                        <!-- Sent Message -->
                                        <div class="flex items-start space-x-3 flex-row-reverse">
                                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="bg-blue-600 text-white rounded-2xl rounded-br-md p-3 ml-auto max-w-md shadow-lg relative">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                    <!-- Tail for sent -->
                                                    <div class="absolute bottom-0 right-0 w-0 h-0 border-l-4 border-l-blue-600 border-t-4 border-t-transparent"></div>
                                                </div>
                                                <div class="flex items-center justify-end space-x-1 mt-1">
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M j, g:i A') }}</p>
                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                @if($message->listing)
                                                    <p class="text-xs text-blue-400 mt-1 text-right">Re: {{ $message->listing->title }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Received Message -->
                                        <div class="flex items-start space-x-3">
                                            <img src="{{ $otherUser->profile_photo_path ? asset('storage/' . $otherUser->profile_photo_path) : asset('images/default-avatar.png') }}" alt="{{ $otherUser->name }}" class="w-8 h-8 rounded-full object-cover">
                                            <div class="flex-1">
                                                <div class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl rounded-bl-md p-3 max-w-md shadow-lg relative">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                    <!-- Tail for received -->
                                                    <div class="absolute bottom-0 left-0 w-0 h-0 border-r-4 border-r-gray-100 dark:border-r-gray-700 border-t-4 border-t-transparent"></div>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $message->created_at->format('M j, g:i A') }}</p>
                                                @if($message->listing)
                                                    <p class="text-xs text-blue-600 dark:text-blue-400">Re: {{ $message->listing->title }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                            @empty
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No messages yet</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start the conversation by sending a message below.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message Input -->
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <form id="message-form" class="space-y-4">
                                @csrf
                                @if($listing)
                                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                @endif
                                <div class="flex space-x-3">
                                    <textarea id="message-input" name="message" rows="3" placeholder="Type your message..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('message', $listing ? 'Hi! I\'m interested in your property "' . $listing->title . '" located at ' . $listing->location . '. Could you please provide more details about availability and any additional information?' : '') }}</textarea>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors h-fit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @if($listing)
                                    <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                        <p class="text-sm text-blue-800 dark:text-blue-200">
                                            <strong>Regarding:</strong> {{ $listing->title }} - ₱{{ number_format($listing->price, 2) }}/month
                                        </p>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="default-message-data" data-message="{{ $listing ? 'Hi! I\'m interested in your property "' . $listing->title . '" located at ' . $listing->location . '. Could you please provide more details about availability and any additional information?' : '' }}" style="display: none;"></div>

<script>
const defaultMessage = document.getElementById('default-message-data').dataset.message;

// Global variables for message UI
let currentUserId = {{ auth()->id() }};
let otherUserId = {{ $otherUser->id }};
let currentUserInitial = "{{ substr(auth()->user()->name, 0, 1) }}";
let otherUserAvatar = "{{ $otherUser->profile_photo_path ? asset('storage/' . $otherUser->profile_photo_path) : asset('images/default-avatar.png') }}";
let otherUserName = "{{ $otherUser->name }}";

// Auto-scroll to bottom of messages
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Listen for real-time messages
    const channelName = 'conversation.' + Math.min(currentUserId, otherUserId) + '.' + Math.max(currentUserId, otherUserId);

    console.log('Echo instance:', window.Echo);
    console.log('Current user ID:', currentUserId);
    console.log('Other user ID:', otherUserId);
    console.log('Channel name:', channelName);

    // Check if Echo is connected
    if (window.Echo) {
        console.log('Echo connector:', window.Echo.connector);

        // Listen for connection events
        window.Echo.connector.pusher.connection.bind('connected', function() {
            console.log('WebSocket connected successfully');
        });

        window.Echo.connector.pusher.connection.bind('disconnected', function() {
            console.log('WebSocket disconnected');
        });

        window.Echo.connector.pusher.connection.bind('error', function(error) {
            console.error('WebSocket connection error:', error);
        });

        // Try to join the private channel
        const channel = window.Echo.private(channelName);
        console.log('Channel instance:', channel);

        channel.listen('.message.sent', function(e) {
            console.log('Received message event:', e);
            // Only add message if it's not from the current user (to avoid duplicates)
            if (e.message.sender_id != currentUserId) {
                addMessageToUI(e.message, false);
            }
        })
        .error(function(error) {
            console.error('Channel error:', error);
        });

        // Test authentication
        channel.subscribed(function() {
            console.log('Successfully subscribed to channel:', channelName);
        });

        channel.error(function(error) {
            console.error('Channel subscription error:', error);
        });
    } else {
        console.error('Echo is not available');
    }

    // Handle form submission via AJAX
    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const messageInput = document.getElementById('message-input');
            const listingId = document.querySelector('input[name="listing_id"]')?.value || '';

            const message = messageInput.value.trim();

            if (!message) {
                alert('Please enter a message');
                return;
            }

            // Disable form while sending
            const submitButton = messageForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';

            fetch(`{{ route('tenant.messages.send', $otherUser->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    listing_id: listingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to UI immediately
                    addMessageToUI(data.message, true);

                    // Clear form
                    messageInput.value = '';

                    // Reset message to default if listing exists
                    if (defaultMessage) {
                        messageInput.value = defaultMessage;
                    }
                } else {
                    alert('Failed to send message: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            })
            .finally(() => {
                // Re-enable form
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }
});

function addMessageToUI(message, isSender) {
    const messagesContainer = document.getElementById('messages-container');
    if (!messagesContainer) {
        return;
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3' + (isSender ? ' flex-row-reverse' : '');

    const avatarDiv = document.createElement('div');
    if (isSender) {
        avatarDiv.className = 'w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center';
        avatarDiv.innerHTML = '<span class="text-white text-sm font-bold">' + currentUserInitial + '</span>';
    } else {
        avatarDiv.className = 'w-8 h-8 rounded-full object-cover';
        avatarDiv.innerHTML = '<img src="' + otherUserAvatar + '" alt="' + otherUserName + '" class="w-8 h-8 rounded-full object-cover">';
    }

    const contentDiv = document.createElement('div');
    contentDiv.className = 'flex-1';

    const messageBubble = document.createElement('div');
    messageBubble.className = isSender ? 'bg-blue-600 text-white rounded-lg p-3 ml-auto max-w-md' : 'bg-gray-100 dark:bg-gray-700 rounded-lg p-3';

    const messageP = document.createElement('p');
    messageP.className = 'text-sm ' + (isSender ? '' : 'text-gray-900 dark:text-white');
    messageP.textContent = message.message;
    messageBubble.appendChild(messageP);

    contentDiv.appendChild(messageBubble);

    const timestampP = document.createElement('p');
    timestampP.className = 'text-xs text-gray-500 dark:text-gray-400 mt-1' + (isSender ? ' text-right' : '');
    // Format timestamp
    const date = new Date(message.created_at);
    timestampP.textContent = date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    contentDiv.appendChild(timestampP);

    messageDiv.appendChild(avatarDiv);
    messageDiv.appendChild(contentDiv);

    messagesContainer.appendChild(messageDiv);

    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
</script>
@endsection
