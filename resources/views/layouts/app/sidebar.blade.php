<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        @include('partials.head')

        <script>
    (() => {
        const theme = localStorage.getItem('theme');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar
            sticky
            collapsible="mobile"
            class="border-e border-zinc-200 bg-ki-verde dark:border-zinc-700 dark:bg-zinc-900"
        >

            <flux:sidebar.header>

                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />

                <flux:sidebar.collapse class="lg:hidden" />

            </flux:sidebar.header>

            <flux:sidebar.nav>

                <flux:sidebar.group :heading="__('Principal')" class="grid">

                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                        class="data-[current=true]:bg-ki-verde"
                        
                    >

                        Dashboard

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="clipboard-document-list"
                        :href="route('establishments.orders.index', 1)"
                        :current="request()->routeIs('establishments.orders.*')"
                        wire:navigate
                    >

                        Pedidos

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="squares-2x2"
                        :href="route('establishments.products.index', 1)"
                        :current="request()->routeIs('establishments.products.*')"
                        wire:navigate
                    >

                        Cardápio

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="table-cells"
                        :href="route('establishments.tables.index', 1)"
                        :current="request()->routeIs('establishments.tables.*')"
                        wire:navigate
                    >

                        Mesas

                    </flux:sidebar.item>

                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Gestão')" class="grid">

                    <flux:sidebar.item
                        icon="tag"
                        :href="route('establishments.categories.index', 1)"
                        :current="request()->routeIs('establishments.categories.*')"
                        wire:navigate
                    >

                        Categorias

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="users"
                        :href="route('establishments.employees.index', 1)"
                        :current="request()->routeIs('establishments.employees.*')"
                        wire:navigate
                    >

                        Funcionários

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="qr-code"
                        :href="route('establishments.qr-codes', 1)"
                        :current="request()->routeIs('establishments.qr-codes')"
                        wire:navigate
                    >

                        QR Codes

                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="building-storefront"
                        :href="route('establishments.index')"
                        :current="request()->routeIs('establishments.*') && !request()->routeIs('establishments.orders.*') && !request()->routeIs('establishments.products.*') && !request()->routeIs('establishments.tables.*') && !request()->routeIs('establishments.categories.*') && !request()->routeIs('establishments.employees.*') && !request()->routeIs('establishments.qr-codes')"
                        wire:navigate
                    >

                        Estabelecimentos

                    </flux:sidebar.item>

                </flux:sidebar.group>

            </flux:sidebar.nav>

            <flux:spacer />
            <flux:spacer />

<flux:sidebar.nav>

    <!-- INTERRUPTOR DE TEMA -->
    <button
    type="button"
    id="theme-toggle"
    aria-label="Alternar tema"
    class="relative flex h-9 w-16 items-center rounded-full bg-white/20 p-1 transition-colors duration-300 hover:bg-white/30"
>
    <span
        id="theme-toggle-circle"
        class="flex size-7 translate-x-0 items-center justify-center rounded-full bg-white shadow-md transition-transform duration-300"
    >
        {{-- Lua --}}
        <svg
            id="theme-moon"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="size-4 text-[#7E7AA8]"
        >
            <path d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 1 0 21.752 15.002Z"/>
        </svg>

        {{-- Sol --}}
        <svg
            id="theme-sun"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            class="hidden size-4 text-[#DFA688]"
        >
            <circle cx="12" cy="12" r="3.5"/>
            <path
                stroke-linecap="round"
                d="M12 2.5v2M12 19.5v2M4.58 4.58l1.42 1.42M18 18l1.42 1.42M2.5 12h2M19.5 12h2M4.58 19.42 6 18M18 6l1.42-1.42"
            />
        </svg>
    </span>
</button>
</flux:sidebar.nav>

            <flux:sidebar.nav>

                <flux:sidebar.item
                    icon="cog"
                    :href="route('profile.edit')"
                    :current="request()->routeIs('profile.edit')"
                    wire:navigate
                >

                    Configurações

                </flux:sidebar.item>

            </flux:sidebar.nav>

            <x-desktop-user-menu
                class="hidden lg:block"
                :name="auth()->user()->name"
            />

        </flux:sidebar>

        <!-- Mobile User Menu -->

        <flux:header class="lg:hidden">

            <flux:sidebar.toggle
                class="lg:hidden"
                icon="bars-2"
                inset="left"
            />

            <flux:spacer />

            <flux:dropdown position="top" align="end">

                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>

                    <flux:menu.radio.group>

                        <div class="p-0 text-sm font-normal">

                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">

                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">

                                    <flux:heading class="truncate">

                                        {{ auth()->user()->name }}

                                    </flux:heading>

                                    <flux:text class="truncate">

                                        {{ auth()->user()->email }}

                                    </flux:text>

                                </div>

                            </div>

                        </div>

                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>

                        <flux:menu.item
                            :href="route('profile.edit')"
                            icon="cog"
                            wire:navigate
                        >

                            Configurações

                        </flux:menu.item>

                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">

                        @csrf

                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >

                            Sair

                        </flux:menu.item>

                    </form>

                </flux:menu>

            </flux:dropdown>

        </flux:header>

        {{ $slot }}

        @persist('toast')

            <flux:toast.group>

                <flux:toast />

            </flux:toast.group>

        @endpersist
<script>
    (() => {
        if (window.kitchoThemeInitialized) return;

        window.kitchoThemeInitialized = true;

        function applyTheme() {
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        }

        function updateThemeButton() {
            const circle = document.getElementById('theme-toggle-circle');
            const moon = document.getElementById('theme-moon');
            const sun = document.getElementById('theme-sun');

            if (!circle || !moon || !sun) return;

            const isDark = document.documentElement.classList.contains('dark');

            if (isDark) {
                circle.classList.remove('translate-x-0');
                circle.classList.add('translate-x-7');

                moon.classList.remove('hidden');
                sun.classList.add('hidden');
            } else {
                circle.classList.remove('translate-x-7');
                circle.classList.add('translate-x-0');

                moon.classList.add('hidden');
                sun.classList.remove('hidden');
            }
        }

        function refreshTheme() {
            applyTheme();
            updateThemeButton();
        }

        // Aplica o tema salvo ao carregar
        refreshTheme();

        // Funciona mesmo quando o Livewire recria o botão
        document.addEventListener('click', (event) => {
            const toggle = event.target.closest('#theme-toggle');

            if (!toggle) return;

            const isDark = document.documentElement.classList.toggle('dark');

            localStorage.setItem(
                'theme',
                isDark ? 'dark' : 'light'
            );

            updateThemeButton();
        });

        // Reaplica a preferência depois do wire:navigate
        document.addEventListener('livewire:navigated', () => {
            refreshTheme();
        });
    })();
</script>

@fluxScripts

    </body>

</html>