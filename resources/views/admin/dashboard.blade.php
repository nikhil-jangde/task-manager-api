<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Welcome</p>
                    <p class="font-semibold text-gray-800">{{ $user->name ?? 'Admin' }}</p>
                </div>
                <form action="/admin/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">User Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Name</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Member Since</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- API Routes Tabs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200">
                <div class="flex flex-wrap overflow-x-auto">
                    @foreach ($apiRoutes as $index => $apiRoute)
                        <button 
                            onclick="loadApiData('{{ $apiRoute['route'] }}')"
                            class="tab-btn px-6 py-4 font-semibold transition duration-200 border-b-2 {{ $index === 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-blue-600' }}"
                            data-route="{{ $apiRoute['route'] }}"
                            data-index="{{ $index }}">
                            {{ $apiRoute['name'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Data Display Area -->
            <div class="p-6">
                <div id="loading" class="text-center py-8">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Loading data...</span>
                    </div>
                </div>
                <div id="data-container" class="hidden">
                    <div class="mb-4 flex gap-2">
                        <button onclick="copyToClipboard()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                            Copy JSON
                        </button>
                    </div>
                    <pre id="data-display" class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto max-h-96"></pre>
                </div>
                <div id="error-container" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-600 font-semibold">Error loading data</p>
                        <p id="error-message" class="text-red-500 text-sm mt-2"></p>
                    </div>
                </div>
            </div>

            <!-- Route Description -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <p id="route-description" class="text-gray-600 text-sm">
                    @if($apiRoutes)
                        {{ $apiRoutes[0]['description'] }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <script>
        const apiRoutes = {!! json_encode($apiRoutes) !!};
        let currentJsonData = null;

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            if (apiRoutes.length > 0) {
                loadApiData(apiRoutes[0].route);
            }
        });

        function loadApiData(route) {
            const loading = document.getElementById('loading');
            const dataContainer = document.getElementById('data-container');
            const errorContainer = document.getElementById('error-container');
            const dataDisplay = document.getElementById('data-display');

            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-600');
            });
            document.querySelector(`[data-route="${route}"]`).classList.add('border-blue-600', 'text-blue-600');
            document.querySelector(`[data-route="${route}"]`).classList.remove('border-transparent', 'text-gray-600');

            // Update description
            const routeInfo = apiRoutes.find(r => r.route === route);
            document.getElementById('route-description').textContent = routeInfo.description;

            loading.classList.remove('hidden');
            dataContainer.classList.add('hidden');
            errorContainer.classList.add('hidden');

            fetch(`/admin/api-data/${route}`)
                .then(response => response.json())
                .then(data => {
                    currentJsonData = data;
                    dataDisplay.textContent = JSON.stringify(data, null, 2);
                    loading.classList.add('hidden');
                    dataContainer.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('error-message').textContent = error.message;
                    loading.classList.add('hidden');
                    errorContainer.classList.remove('hidden');
                });
        }

        function copyToClipboard() {
            if (!currentJsonData) return;
            
            const jsonString = JSON.stringify(currentJsonData, null, 2);
            navigator.clipboard.writeText(jsonString).then(() => {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(() => {
                    btn.textContent = originalText;
                }, 2000);
            });
        }
    </script>
</body>
</html>
