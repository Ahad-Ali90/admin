<x-admin.layouts.app>
    @push('styles')
    <style>
        /* Dark Mode Support */
        [data-theme="dark"] .profile-card {
            background: var(--card-bg) !important;
            color: var(--text-color) !important;
        }

        [data-theme="dark"] h2,
        [data-theme="dark"] h3 {
            color: var(--text-color) !important;
        }

        [data-theme="dark"] p,
        [data-theme="dark"] label {
            color: var(--text-color) !important;
        }

        [data-theme="dark"] .text-gray-600 {
            color: var(--text-color) !important;
            opacity: 0.8;
        }

        [data-theme="dark"] .text-gray-800 {
            color: var(--text-color) !important;
        }

        [data-theme="dark"] .text-red-600 {
            color: #f87171 !important;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] input[type="text"],
        [data-theme="dark"] input[type="email"],
        [data-theme="dark"] input[type="password"] {
            background: var(--input-bg) !important;
            color: var(--text-color) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] input:focus {
            background: var(--input-bg) !important;
            color: var(--text-color) !important;
            border-color: var(--primary) !important;
        }

        [data-theme="dark"] .invalid-feedback {
            color: #f87171 !important;
        }
    </style>
    @endpush

    <div class="container-xxl py-4">
        <div class="mb-4">
            <h2 class="h3 mb-2">{{ __('Profile') }}</h2>
            <p class="text-secondary mb-0">Manage your account settings and preferences</p>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card profile-card">
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card profile-card">
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card profile-card">
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layouts.app>
