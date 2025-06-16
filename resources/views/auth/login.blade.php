<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" onsubmit="return sanitizeForm()">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" oninput="sanitizeInput(this)" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            oninput="sanitizeInput(this)" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- CAPTCHA -->
        <div class="mt-4">
            <x-input-label for="captcha" :value="__('Enter the code shown below')" />
            
            <div class="flex items-center space-x-2">
                <x-text-input id="captcha" class="block mt-1 w-full"
                                type="text"
                                name="captcha"
                                required
                                oninput="sanitizeInput(this)" />
                
                <img src="{{ route('captcha.generate') }}" 
                     alt="CAPTCHA" 
                     class="mt-1 cursor-pointer" 
                     onclick="this.src='{{ route('captcha.generate') }}?'+Math.random()"
                     title="Click to refresh">
            </div>
            
            <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                @if (Route::has('register'))
                    <a class="ms-4 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                        {{ __('Need an account?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function sanitizeInput(input) {
            // Remove any HTML tags
            input.value = input.value.replace(/<[^>]*>/g, '');
            
            // Remove SQL injection patterns
            input.value = input.value.replace(/[\'";]/g, '');
            
            // Remove any potential XSS patterns
            input.value = input.value.replace(/javascript:/gi, '');
            input.value = input.value.replace(/on\w+=/gi, '');
            
            // For email field, ensure it's a valid email format
            if (input.type === 'email') {
                input.value = input.value.toLowerCase().trim();
            }
        }

        function sanitizeForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const captcha = document.getElementById('captcha');
            
            // Sanitize all fields before submission
            sanitizeInput(email);
            sanitizeInput(password);
            sanitizeInput(captcha);
            
            // Additional validation
            if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                alert('Please enter a valid email address');
                return false;
            }
            
            if (password.value.length < 8) {
                alert('Password must be at least 8 characters long');
                return false;
            }

            if (captcha.value.length !== 4) {
                alert('Please enter the 4-character CAPTCHA code');
                return false;
            }
            
            return true;
        }
    </script>
</x-guest-layout>
