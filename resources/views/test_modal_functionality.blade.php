@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <h1 class="text-2xl font-bold mb-6">Test Modal Functionality</h1>

    <!-- Test Button -->
    <button onclick="testModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Test Modal for User ID 8
    </button>

    <div class="mt-8 p-4 bg-white rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Debug Info:</h2>
        <div id="debug-info" class="text-sm text-gray-600"></div>
    </div>
</div>

<script>
    function testModal() {
        const debugInfo = document.getElementById('debug-info');
        debugInfo.innerHTML = 'Testing modal for user ID 8...<br>';

        // Test the API call directly
        fetch('/admin/users/8/verification-details')
            .then(response => {
                debugInfo.innerHTML += 'API Response Status: ' + response.status + '<br>';
                return response.json();
            })
            .then(data => {
                debugInfo.innerHTML += 'API Response: ' + JSON.stringify(data, null, 2) + '<br>';

                if (data.success) {
                    const userData = data.data;
                    debugInfo.innerHTML += '<br>User Data:<br>';
                    debugInfo.innerHTML += 'Document Type: ' + (userData.document_type || 'Not specified') + '<br>';
                    debugInfo.innerHTML += 'Front Image URL: ' + (userData.front_image_url || 'No URL') + '<br>';
                    debugInfo.innerHTML += 'Back Image URL: ' + (userData.back_image_url || 'No URL') + '<br>';

                    // Now test the modal display
                    document.getElementById('modalFrontImage').src = userData.front_image_url || '/images/placeholder-id.png';
                    document.getElementById('modalBackImage').src = userData.back_image_url || '/images/placeholder-id.png';
                    document.getElementById('modalDocumentType').textContent = userData.document_type || 'Not specified';
                    document.getElementById('modalVerificationNotes').textContent = userData.verification_notes || 'No additional notes.';
                    document.getElementById('verificationDetailsModal').classList.remove('hidden');

                    debugInfo.innerHTML += '<br>Modal should now be visible!';
                } else {
                    debugInfo.innerHTML += '<br>API returned error: ' + (data.message || 'Unknown error');
                }
            })
            .catch(error => {
                debugInfo.innerHTML += '<br>Error: ' + error.message;
                console.error('Modal test error:', error);
            });
    }

    function closeVerificationModal() {
        document.getElementById('verificationDetailsModal').classList.add('hidden');
    }
</script>
@endsection
