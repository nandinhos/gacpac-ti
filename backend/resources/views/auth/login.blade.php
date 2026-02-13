<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#001a4d] via-[#002776] to-black relative overflow-hidden">
        <!-- Decorative elements for glass effect -->
        <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-fab-blue/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] overflow-hidden sm:rounded-2xl z-10">
            <div class="mb-10 text-center">
                <div class="flex justify-center mb-6">
                    <x-application-logo class="w-24 h-24 fill-current text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.5)]" />
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-widest uppercase italic">
                    {{ __('SGTI-GAC') }}
                </h2>
                <div class="h-1 w-20 bg-white/30 mx-auto mt-2 rounded-full"></div>
                <p class="text-sm text-blue-100/70 mt-4 font-medium uppercase tracking-tighter">
                    {{ __('Sistema de Gestão de TI - GAC-PAC') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Identidade Militar / E-mail')" class="text-[10px] font-bold uppercase tracking-widest text-blue-200/60 mb-1 ml-1" />
                    <x-text-input id="email" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-blue-300/30 focus:border-white/40 focus:ring-white/20 backdrop-blur-sm transition-all duration-300 rounded-xl py-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="usuario@fab.mil.br" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-xs" />
                </div>

                <!-- Password -->
                <div class="group">
                    <div class="flex items-center justify-between mb-1 ml-1">
                        <x-input-label for="password" :value="__('Senha')" class="text-[10px] font-bold uppercase tracking-widest text-blue-200/60" />
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-bold uppercase tracking-widest text-blue-300/50 hover:text-white transition-colors" href="{{ route('password.request') }}">
                                {{ __('Recuperar Acesso') }}
                            </a>
                        @endif
                    </div>
                    <x-text-input id="password" class="block mt-1 w-full bg-white/5 border-white/10 text-white placeholder-blue-300/30 focus:border-white/40 focus:ring-white/20 backdrop-blur-sm transition-all duration-300 rounded-xl py-3"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-xs" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center ml-1">
                    <input id="remember_me" type="checkbox" class="rounded-lg border-white/10 bg-white/5 text-fab-blue shadow-sm focus:ring-white/20 w-4 h-4" name="remember">
                    <span class="ms-3 text-xs font-semibold text-blue-100/60 uppercase tracking-widest">{{ __('Manter conectado') }}</span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-white/10 rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.2)] text-sm font-black text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-300 uppercase tracking-[0.2em]">
                        {{ __('Acessar Sistema') }}
                    </button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-white/10 text-center space-y-2">
                <p class="text-[9px] text-blue-200/30 uppercase tracking-[0.3em] font-medium leading-relaxed">
                    {{ __('Setor de Tecnologia da Informação') }}
                </p>
                <div class="flex items-center justify-center space-x-2">
                    <span class="h-[1px] w-4 bg-white/10"></span>
                    <p class="text-[8px] text-white/20 font-bold uppercase tracking-tighter">
                        © {{ date('Y') }} Força Aérea Brasileira
                    </p>
                    <span class="h-[1px] w-4 bg-white/10"></span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
