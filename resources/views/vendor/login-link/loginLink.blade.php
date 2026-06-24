@env(config('login-link.allowed_environments'))
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm max-w-sm mx-auto">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Complete your Login</h3>
        
        <form method="POST" action="{{ route('loginLinkLogin') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="key" value="{{ $key }}">
            <input type="hidden" name="redirect_url" value="{{ $redirectUrl }}">
            <input type="hidden" name="guard" value="{{ $guard }}">
            <input type="hidden" name="user_attributes" value="{{ json_encode($userAttributes) }}">
            <input type="hidden" name="user_model" value="{{ $userModel }}">

            <input type="hidden" name="ip_address" value="{{ request()->ip() }}">

            <div class="mt-4">
                @include('login-link::loginLinkButton')
            </div>
        </form>
    </div>
@endenv