<!-- resources/views/auth/login.blade.php -->

<section class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>

        <!-- Text Section -->
        <p class="text-gray-700 mb-4">
            Welcome back! Please enter your credentials to log in to your account.
        </p>

        <!-- Additional Links -->
        <div class="flex justify-center mb-4">
            {{-- @if (Route::has('register'))
                <a class="text-sm text-indigo-500 hover:text-indigo-700 mr-4" href="{{ route('register') }}">
                    Create an account
                </a>
            @endif
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-500 hover:text-indigo-700" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif --}}
        </div>

        <!-- Login Button -->
        <div>
            {{-- <button type="submit"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Login
            </button> --}}
        </div>
    </div>
</section>
