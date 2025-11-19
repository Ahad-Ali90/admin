<x-guest-layout>
    <!-- Light Background Container -->
    <div class="login-container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%, #f8fafc 100%);">
        
        <!-- Subtle Pattern Overlay -->
        <div class="pattern-overlay" style="position: absolute; inset: 0; background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(79, 70, 229, 0.02) 10px, rgba(79, 70, 229, 0.02) 20px); z-index: 1;"></div>

        <!-- Background Image Overlay (Light) -->
        <div class="bg-image-overlay" style="position: absolute; inset: 0; background-image: url('https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0.08; z-index: 0; filter: grayscale(100%) brightness(1.2);"></div>
        
        <!-- Animated Transport Elements (Subtle) -->
        <div class="animated-elements" style="position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 2;">
            <!-- Moving Truck 1 -->
            <div class="moving-truck truck-1" style="position: absolute; bottom: 20%; left: -150px; width: 180px; height: 90px; opacity: 0.08; animation: moveTruck 30s linear infinite;">
                <svg viewBox="0 0 200 100" style="width: 100%; height: 100%;">
                    <path d="M20 70 L180 70 L180 50 L160 30 L140 30 L130 20 L70 20 L60 30 L40 30 L20 50 Z" fill="#4f46e5" opacity="0.6"/>
                    <rect x="80" y="30" width="40" height="20" fill="#6366f1" opacity="0.5"/>
                    <circle cx="50" cy="70" r="15" fill="#1e293b"/>
                    <circle cx="150" cy="70" r="15" fill="#1e293b"/>
                </svg>
            </div>
            
            <!-- Moving Truck 2 -->
            <div class="moving-truck truck-2" style="position: absolute; bottom: 30%; left: -200px; width: 160px; height: 80px; opacity: 0.06; animation: moveTruck 35s linear infinite; animation-delay: 8s;">
                <svg viewBox="0 0 200 100" style="width: 100%; height: 100%;">
                    <path d="M20 70 L180 70 L180 50 L160 30 L140 30 L130 20 L70 20 L60 30 L40 30 L20 50 Z" fill="#6366f1" opacity="0.5"/>
                    <rect x="80" y="30" width="40" height="20" fill="#818cf8" opacity="0.4"/>
                    <circle cx="50" cy="70" r="15" fill="#1e293b"/>
                    <circle cx="150" cy="70" r="15" fill="#1e293b"/>
                </svg>
            </div>

            <!-- Floating Boxes (House Removal Theme) - Subtle -->
            <div class="floating-box box-1" style="position: absolute; top: 15%; right: -80px; width: 60px; height: 60px; opacity: 0.06; animation: floatBox 25s ease-in-out infinite;">
                <svg viewBox="0 0 100 100" style="width: 100%; height: 100%;">
                    <rect x="20" y="30" width="60" height="50" fill="#4f46e5" opacity="0.5"/>
                    <rect x="25" y="35" width="15" height="15" fill="#6366f1" opacity="0.4"/>
                    <rect x="60" y="35" width="15" height="15" fill="#6366f1" opacity="0.4"/>
                    <rect x="25" y="55" width="50" height="20" fill="#818cf8" opacity="0.3"/>
                    <polygon points="20,30 50,10 80,30" fill="#4f46e5" opacity="0.6"/>
                </svg>
            </div>
        </div>

        <!-- Main Content -->
        <div class="login-wrapper" style="width: 100%; max-width: 28rem; padding: 1.5rem; position: relative; z-index: 10;">
            <!-- Clean White Login Card - Sharp Corners -->
            <div class="login-card" style="background: #ffffff; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05); border: 2px solid #e2e8f0; padding: 3rem 2.5rem; animation: slideUp 0.6s ease-out; transition: all 0.3s; position: relative;">
                
                <!-- Top Accent Bar -->
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%);"></div>

                <!-- Logo/Header Section -->
                <div class="header-section" style="text-align: center; margin-bottom: 2.5rem; animation: fadeIn 0.8s ease-out;">
                    <!-- Logo Container - Sharp Corners -->
                    <div class="logo-container" style="display: inline-flex; align-items: center; justify-content: center; padding: 1rem 1.5rem; background: #ffffff; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s; position: relative; overflow: hidden; border: 2px solid #e2e8f0;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0, 0, 0, 0.12)'; this.style.borderColor='#4f46e5'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.08)'; this.style.borderColor='#e2e8f0'">
                        <!-- Logo Image -->
                        <img 
                            src="https://removalplus365.com/wp-content/uploads/2021/12/Removal-365-1000-x-1000-px-2.png" 
                            alt="TBR Transport Logo" 
                            style="max-width: 180px; height: auto; display: block; object-fit: contain;"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                        >
                        <h2 style="display: none; font-size: 1.875rem; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: 2px; text-transform: uppercase;">
                            TBR Transport
                        </h2>
                    </div>
                    
                    <!-- Welcome Title -->
                    <h1 class="welcome-title" style="font-size: 2rem; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px; margin-bottom: 0.5rem;">
                        Welcome Back
                    </h1>
                    <p style="color: #64748b; margin: 0; font-size: 1rem; font-weight: 500;">
                        Sign in to continue your journey
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" style="color: #1e293b; background: #f1f5f9; padding: 1rem; border: 2px solid #e2e8f0; border-left: 4px solid #4f46e5;" />

                <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group" style="animation: slideUp 0.5s ease-out 0.2s both;">
                        <label 
                            for="email" 
                            style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;"
                        >
                            Email Address
                        </label>
                        <div style="position: relative;">
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                class="form-input"
                                style="display: block; width: 100%; padding: 1rem 1rem 1rem 3rem; color: #1e293b; background: #ffffff; border: 2px solid #e2e8f0; transition: all 0.3s; outline: none; font-size: 1rem; font-weight: 500; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'; this.style.background='#fafafa'; document.getElementById('email-icon').style.color='#4f46e5';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#ffffff'; document.getElementById('email-icon').style.color='#64748b';"
                            />
                            <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #64748b; pointer-events: none; transition: color 0.3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="email-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <x-input-error :messages="$errors->get('email')" style="margin-top: 0.5rem; color: #dc2626; font-size: 0.875rem; font-weight: 600;" />
                    </div>

                    <!-- Password -->
                    <div class="form-group" style="animation: slideUp 0.5s ease-out 0.3s both;">
                        <label 
                            for="password" 
                            style="display: block; font-size: 0.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;"
                        >
                            Password
                        </label>
                        <div style="position: relative;">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                class="form-input"
                                style="display: block; width: 100%; padding: 1rem 3.5rem 1rem 3rem; color: #1e293b; background: #ffffff; border: 2px solid #e2e8f0; transition: all 0.3s; outline: none; font-size: 1rem; font-weight: 500; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'; this.style.background='#fafafa'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='#ffffff'"
                            />
                            <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #64748b; pointer-events: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="password-toggle-btn"
                                style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #64748b; background: transparent; border: none; cursor: pointer; transition: all 0.2s; padding: 0.5rem; width: 2.25rem; height: 2.25rem; display: flex; align-items: center; justify-content: center;"
                                onmouseover="this.style.color='#4f46e5'; this.style.background='#f1f5f9'"
                                onmouseout="this.style.color='#64748b'; this.style.background='transparent'"
                            >
                                <svg id="eye-icon" style="width: 1.25rem; height: 1.25rem; color: inherit;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-off-icon" style="width: 1.25rem; height: 1.25rem; display: none; color: inherit;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.906 5.236m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" style="margin-top: 0.5rem; color: #dc2626; font-size: 0.875rem; font-weight: 600;" />
                    </div>

                    <!-- Remember Me -->
                    <div class="form-options" style="display: flex; align-items: center; justify-content: flex-start; animation: slideUp 0.5s ease-out 0.4s both;">
                        <label for="remember_me" class="remember-me-label" style="display: inline-flex; align-items: center; cursor: pointer; gap: 0.75rem; position: relative;">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember"
                                class="remember-checkbox"
                                style="width: 1.25rem; height: 1.25rem; margin: 0; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none; background: #ffffff; border: 2px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; flex-shrink: 0;"
                                onchange="updateCheckboxState(this)"
                                onfocus="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                            >
                            <span class="checkbox-checkmark" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; pointer-events: none; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;">
                                <svg style="width: 0.875rem; height: 0.875rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <span style="font-size: 0.9375rem; color: #475569; font-weight: 600; transition: color 0.2s; user-select: none;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#475569'">
                                {{ __('Remember me') }}
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button - Sharp Corners -->
                    <div style="animation: slideUp 0.5s ease-out 0.5s both;">
                        <button 
                            type="submit" 
                            class="submit-btn"
                            style="width: 100%; padding: 1rem 1.5rem; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%); color: white; font-weight: 700; font-size: 1rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); border: 2px solid #4f46e5; cursor: pointer; transition: all 0.3s; position: relative; overflow: hidden; text-transform: uppercase; letter-spacing: 1px;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(79, 70, 229, 0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)'"
                            onmousedown="this.style.transform='translateY(0) scale(0.98)'"
                            onmouseup="this.style.transform='translateY(-2px) scale(1)'"
                        >
                            <span style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                                <span>{{ __('Log in') }}</span>
                                <svg id="arrow-icon" style="width: 1.25rem; height: 1.25rem; transition: transform 0.3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Keyframe Animations */
        @keyframes moveTruck {
            0% {
                transform: translateX(-200px);
            }
            100% {
                transform: translateX(calc(100vw + 200px));
            }
        }

        @keyframes floatBox {
            0%, 100% {
                transform: translateX(0) translateY(0) rotate(0deg);
            }
            25% {
                transform: translateX(20px) translateY(-30px) rotate(5deg);
            }
            50% {
                transform: translateX(-10px) translateY(-50px) rotate(-5deg);
            }
            75% {
                transform: translateX(15px) translateY(-30px) rotate(3deg);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Enhanced Styles */
        .login-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.08) !important;
            transform: translateY(-2px);
        }

        .submit-btn:hover #arrow-icon {
            transform: translateX(6px);
        }

        .submit-btn:active {
            transform: translateY(0) scale(0.98);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
            background: #fafafa !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-wrapper {
                padding: 1rem !important;
            }
            
            .login-card {
                padding: 2.5rem 2rem !important;
            }
            
            .logo-container {
                padding: 1rem 1.5rem !important;
            }
            
            .logo-container img {
                max-width: 160px !important;
            }
            
            .logo-container h2 {
                font-size: 1.5rem !important;
            }
            
            .welcome-title {
                font-size: 1.75rem !important;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem !important;
            }
            
            .logo-container {
                padding: 1rem 1.25rem !important;
            }
            
            .logo-container img {
                max-width: 140px !important;
            }
            
            .logo-container h2 {
                font-size: 1.125rem !important;
                letter-spacing: 1px !important;
            }
            
            .welcome-title {
                font-size: 1.5rem !important;
            }
            
            input[type="email"],
            input[type="password"] {
                font-size: 16px !important; /* Prevents zoom on iOS */
            }
        }

        @media (min-width: 1024px) {
            .login-wrapper {
                max-width: 44.5rem !important; /* 28rem + 200px = 712px */
            }
            
            .login-card {
                padding: 3.5rem 3rem !important;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Input focus states */
        .form-input {
            border-color: #e2e8f0 !important;
        }

        /* Custom Checkbox Styles */
        .remember-checkbox:checked {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%) !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3) !important;
        }

        .remember-checkbox:checked + .checkbox-checkmark {
            opacity: 1 !important;
        }

        .remember-checkbox:hover:not(:checked) {
            border-color: #4f46e5 !important;
            background: #f8fafc !important;
        }

        .remember-me-label:hover .remember-checkbox:not(:checked) {
            border-color: #4f46e5 !important;
        }

        .remember-checkbox:active {
            transform: scale(0.95);
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeOffIcon.style.display = 'none';
                eyeIcon.style.display = 'block';
            }
        }

        function updateCheckboxState(checkbox) {
            const checkmark = checkbox.nextElementSibling;
            if (checkbox.checked) {
                checkbox.style.background = 'linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #818cf8 100%)';
                checkbox.style.borderColor = '#4f46e5';
                checkbox.style.boxShadow = '0 2px 8px rgba(79, 70, 229, 0.3)';
                if (checkmark) {
                    checkmark.style.opacity = '1';
                }
            } else {
                checkbox.style.background = '#ffffff';
                checkbox.style.borderColor = '#e2e8f0';
                checkbox.style.boxShadow = 'none';
                if (checkmark) {
                    checkmark.style.opacity = '0';
                }
            }
        }

        // Initialize checkbox state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('remember_me');
            if (checkbox) {
                updateCheckboxState(checkbox);
            }
        });

        // Handle form interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add parallax effect on scroll
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const bgOverlay = document.querySelector('.bg-image-overlay');
                if (bgOverlay) {
                    bgOverlay.style.transform = `translateY(${scrolled * 0.2}px)`;
                }
            });
        });
    </script>
</x-guest-layout>
