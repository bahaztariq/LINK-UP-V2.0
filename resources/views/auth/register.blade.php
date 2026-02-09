<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-2xl border border-slate-200">
        <div class="flex justify-center mb-6">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">LinkUP</h1>
        </div>
        
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name" class="block font-medium text-sm text-slate-700">Name</label>
                <input id="name" class="block mt-1 w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-primary rounded-xl px-4 py-2.5" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <label for="email" class="block font-medium text-sm text-slate-700">Email</label>
                <input id="email" class="block mt-1 w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-primary rounded-xl px-4 py-2.5" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-slate-700">Password</label>
                <input id="password" class="block mt-1 w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-primary rounded-xl px-4 py-2.5" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label for="password_confirmation" class="block font-medium text-sm text-slate-700">Confirm Password</label>
                <input id="password_confirmation" class="block mt-1 w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-primary rounded-xl px-4 py-2.5" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <label for="terms" class="flex items-center">
                        <input type="checkbox" name="terms" id="terms" required class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                        <div class="ms-2 text-sm text-slate-600">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-primary hover:text-slate-900">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-primary hover:text-slate-900">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </div>
                    </label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-6">
                <a class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="ms-4 bg-primary text-white font-bold py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    {{ __('Register') }}
                </button>
            </div>
        </form>

        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-slate-500 font-medium">Or continue with</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all duration-200 group">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 me-2" alt="Google">
                    <span class="text-sm font-bold text-slate-700">Google</span>
                </a>
                
                <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center px-4 py-2.5 bg-[#1877F2] rounded-xl hover:bg-[#1877F2]/90 transition-all duration-200 group">
                    <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5 me-2 brightness-0 invert" alt="Facebook">
                    <span class="text-sm font-bold text-white">Facebook</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
