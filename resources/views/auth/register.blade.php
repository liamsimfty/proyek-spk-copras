<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                onkeyup="checkPasswordStrength()" />
                
                <!-- Password Toggle Button -->
                <button type="button" 
                        onclick="togglePassword('password')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg id="password-eye" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Password Requirements -->
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                <p class="mb-1">{{ __('Password must contain:') }}</p>
                <ul class="list-disc list-inside space-y-1">
                    <li id="length-req" class="text-red-500">{{ __('At least 8 characters') }}</li>
                    <li id="uppercase-req" class="text-red-500">{{ __('One uppercase letter') }}</li>
                    <li id="lowercase-req" class="text-red-500">{{ __('One lowercase letter') }}</li>
                    <li id="number-req" class="text-red-500">{{ __('One number') }}</li>
                    <li id="special-req" class="text-red-500">{{ __('One special character (!@#$%^&*)') }}</li>
                </ul>
            </div>

            <!-- Password Strength Indicator -->
            <div class="mt-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Strength:') }}</span>
                    <div class="flex space-x-1">
                        <div id="strength-1" class="w-6 h-2 bg-gray-200 rounded"></div>
                        <div id="strength-2" class="w-6 h-2 bg-gray-200 rounded"></div>
                        <div id="strength-3" class="w-6 h-2 bg-gray-200 rounded"></div>
                        <div id="strength-4" class="w-6 h-2 bg-gray-200 rounded"></div>
                    </div>
                    <span id="strength-text" class="text-sm text-gray-500">{{ __('Weak') }}</span>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" 
                                required autocomplete="new-password"
                                onkeyup="checkPasswordMatch()" />
                
                <!-- Password Toggle Button -->
                <button type="button" 
                        onclick="togglePassword('password_confirmation')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg id="password_confirmation-eye" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Password Match Indicator -->
            <div id="password-match" class="mt-1 text-sm" style="display: none;">
                <span id="match-text"></span>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="register-btn" disabled>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript for Password Functionality -->
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                field.type = 'password';
                eye.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            // Update requirement indicators
            updateRequirement('length-req', requirements.length);
            updateRequirement('uppercase-req', requirements.uppercase);
            updateRequirement('lowercase-req', requirements.lowercase);
            updateRequirement('number-req', requirements.number);
            updateRequirement('special-req', requirements.special);

            // Calculate strength
            const score = Object.values(requirements).filter(Boolean).length;
            updateStrengthIndicator(score);
            
            // Check if form can be submitted
            checkFormValidity();
        }

        function updateRequirement(elementId, met) {
            const element = document.getElementById(elementId);
            if (met) {
                element.className = 'text-green-500';
            } else {
                element.className = 'text-red-500';
            }
        }

        function updateStrengthIndicator(score) {
            const indicators = ['strength-1', 'strength-2', 'strength-3', 'strength-4'];
            const text = document.getElementById('strength-text');
            
            // Reset all indicators
            indicators.forEach(id => {
                document.getElementById(id).className = 'w-6 h-2 bg-gray-200 rounded';
            });

            // Update based on score
            if (score >= 1) {
                document.getElementById('strength-1').className = 'w-6 h-2 bg-red-500 rounded';
                text.textContent = 'Weak';
                text.className = 'text-sm text-red-500';
            }
            if (score >= 2) {
                document.getElementById('strength-2').className = 'w-6 h-2 bg-yellow-500 rounded';
                text.textContent = 'Fair';
                text.className = 'text-sm text-yellow-500';
            }
            if (score >= 3) {
                document.getElementById('strength-3').className = 'w-6 h-2 bg-blue-500 rounded';
                text.textContent = 'Good';
                text.className = 'text-sm text-blue-500';
            }
            if (score >= 4) {
                document.getElementById('strength-4').className = 'w-6 h-2 bg-green-500 rounded';
                text.textContent = 'Strong';
                text.className = 'text-sm text-green-500';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const matchText = document.getElementById('match-text');

            if (confirmation.length > 0) {
                matchDiv.style.display = 'block';
                if (password === confirmation) {
                    matchText.textContent = '✓ Passwords match';
                    matchText.className = 'text-green-500';
                } else {
                    matchText.textContent = '✗ Passwords do not match';
                    matchText.className = 'text-red-500';
                }
            } else {
                matchDiv.style.display = 'none';
            }
            
            checkFormValidity();
        }

        function checkFormValidity() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const registerBtn = document.getElementById('register-btn');
            
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            const allRequirementsMet = Object.values(requirements).every(Boolean);
            const passwordsMatch = password === confirmation && confirmation.length > 0;
            
            if (allRequirementsMet && passwordsMatch) {
                registerBtn.disabled = false;
                registerBtn.className = registerBtn.className.replace('opacity-50 cursor-not-allowed', '');
            } else {
                registerBtn.disabled = true;
                if (!registerBtn.className.includes('opacity-50')) {
                    registerBtn.className += ' opacity-50 cursor-not-allowed';
                }
            }
        }
    </script>
</x-guest-layout>