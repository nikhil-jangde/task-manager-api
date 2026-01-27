<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-800 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-500 mt-2">Sign in to your account</p>
            </div>
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="/admin">
                @csrf
                
                <div class="mb-5">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="admin@example.com">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="Enter your password">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200 transform hover:scale-105">
                    Sign In
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-gray-600 text-sm">
                    Demo credentials: <br>
                    <span class="text-blue-600 font-mono">admin@example.com</span> / <span class="text-blue-600 font-mono">password</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
