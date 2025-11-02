@extends('layouts.landlord')

@section('content')
<div class="w-full -m-4 ml-0">
    <!-- Page Header -->
    <div class="mb-8">
        {{-- Removed the header text as per user request --}}
        {{-- <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Profile</h1> --}}
        {{-- <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your account settings and personal information</p> --}}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Overview Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center">
                    <!-- Profile Image -->
                    <div class="relative inline-block">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4 overflow-hidden">
                            @if(Auth::user()->profile_photo_path)
                                <img id="profileImagePreview" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                            @else
                                <span id="profileImagePreview" class="w-full h-full flex items-center justify-center">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <!-- Upload Button Overlay -->
                        <label for="profile_photo" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </label>
                        <input id="profile_photo" type="file" name="profile_photo" class="hidden" accept="image/*">
                    </div>

                    <!-- User Info -->
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>

                    <!-- Verification Status -->
                    <div class="mt-4">
                        @if(Auth::user()->verification_status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Verified
                            </span>
                        @elseif(Auth::user()->verification_status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Pending Verification
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 00.707.293 1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Not Verified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Update Profile Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal details and contact information</p>
                </div>

                <form id="profileInfoForm" method="POST" action="{{ route('landlord.profile.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Success/Error Messages -->
                    <div id="profileInfoMessages">
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Type</label>
                            <input type="text" value="{{ ucfirst(Auth::user()->role) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:text-gray-300 cursor-not-allowed"
                                   readonly>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" id="profileInfoSubmitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ensure your account is secure with a strong password</p>
                </div>

                <form method="POST" action="{{ route('landlord.password.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Success/Error Messages -->
                    @if(session('password_success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('password_success') }}
                        </div>
                    @endif

                    @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Must be at least 8 characters long</p>
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Image Upload Form (Hidden) -->
<form id="profilePhotoForm" method="POST" action="{{ route('landlord.profile.update') }}" enctype="multipart/form-data" class="hidden">
    @csrf
    @method('PUT')
    <input type="file" name="profile_photo" id="hiddenProfilePhotoInput">
</form>

<script>

// Profile image preview and upload
document.getElementById('profile_photo').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }

        // Show preview
        const preview = document.getElementById('profileImagePreview');
        if (preview.tagName === 'IMG') {
            preview.src = URL.createObjectURL(file);
        } else {
            const img = document.createElement('img');
            img.id = 'profileImagePreview';
            img.className = 'w-full h-full object-cover rounded-full';
            img.src = URL.createObjectURL(file);
            preview.replaceWith(img);
        }

        // Create FormData and submit via AJAX
        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('{{ route("landlord.profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text().then(text => {
                console.log('Response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text);
                }
            });
        })
        .then(data => {
            console.log('Parsed data:', data);
            if (data.success) {
                // Update sidebar profile photo if it exists
                const sidebarContainer = document.querySelector('.fixed.inset-y-0.left-0.z-50 .w-12.h-12');
                if (sidebarContainer && data.image_url) {
                    // Check if there's already an img element
                    let sidebarImg = sidebarContainer.querySelector('.sidebar-profile-img');
                    if (sidebarImg) {
                        // Update existing img src
                        sidebarImg.src = data.image_url;
                    } else {
                        // Replace the span with an img
                        const sidebarInitial = sidebarContainer.querySelector('.sidebar-profile-initial');
                        if (sidebarInitial) {
                            const img = document.createElement('img');
                            img.src = data.image_url;
                            img.alt = 'Profile Picture';
                            img.className = 'sidebar-profile-img w-full h-full object-cover rounded-full';
                            sidebarInitial.replaceWith(img);
                        }
                    }
                }
                // Update sidebar username text dynamically
                const sidebarUsername = document.querySelector('.fixed.inset-y-0.left-0.z-50 .flex.items-center.space-x-3 > div > p.text-sm.font-medium.text-black');
                if (sidebarUsername) {
                    sidebarUsername.textContent = data.name || sidebarUsername.textContent;
                }
                // Update sidebar role text dynamically
                const sidebarRole = document.querySelector('.fixed.inset-y-0.left-0.z-50 .flex.items-center.space-x-3 > div > p.text-xs.text-gray-600');
                if (sidebarRole) {
                    sidebarRole.textContent = data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : sidebarRole.textContent;
                }

                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg z-50';
                successDiv.textContent = 'Profile photo updated successfully!';
                document.body.appendChild(successDiv);
                setTimeout(() => successDiv.remove(), 3000);
            } else {
                throw new Error('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to upload profile photo. Please try again. Error: ' + error.message);
            // Reset preview on error
            location.reload();
        });
    }
});

// Profile info form AJAX submission
document.getElementById('profileInfoForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('profileInfoSubmitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update sidebar profile info
            const sidebarUsername = document.querySelector('.fixed.inset-y-0.left-0.z-50 .flex.items-center.space-x-3 > div > p.text-sm.font-medium.text-black');
            if (sidebarUsername && data.name) {
                sidebarUsername.textContent = data.name;
            }
            const sidebarRole = document.querySelector('.fixed.inset-y-0.left-0.z-50 .flex.items-center.space-x-3 > div > p.text-xs.text-gray-600');
            if (sidebarRole && data.role) {
                sidebarRole.textContent = data.role.charAt(0).toUpperCase() + data.role.slice(1);
            }

            // Show success message
            const messagesDiv = document.getElementById('profileInfoMessages');
            messagesDiv.innerHTML = '';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-4 p-4 rounded bg-green-100 border border-green-400 text-green-700';
            messageDiv.textContent = 'Profile updated successfully!';
            messagesDiv.appendChild(messageDiv);

            // Auto-hide success message after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        } else {
            // Show error message
            const messagesDiv = document.getElementById('profileInfoMessages');
            messagesDiv.innerHTML = '';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-4 p-4 rounded bg-red-100 border border-red-400 text-red-700';
            messageDiv.textContent = 'Failed to update profile: ' + (data.message || 'Unknown error');
            messagesDiv.appendChild(messageDiv);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        const messagesDiv = document.getElementById('profileInfoMessages');
        messagesDiv.innerHTML = '';
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-4 p-4 rounded bg-red-100 border border-red-400 text-red-700';
        messageDiv.textContent = 'Failed to update profile. Please try again.';
        messagesDiv.appendChild(messageDiv);
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection
