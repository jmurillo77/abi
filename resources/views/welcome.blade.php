<x-guest-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-10 lg:px-8">
            <header class="flex items-center justify-between border-b border-white/10 pb-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-cyan-300">{{ config('app.name', 'AbiSystem') }}</p>
                    <h1 class="mt-2 text-2xl font-semibold text-white">ERP & CRM</h1>
                </div>

                <nav class="flex items-center gap-3 text-sm font-medium">
                    @auth
                        <a href="{{ route('menu') }}" class="rounded-full bg-cyan-400 px-4 py-2 text-slate-950 transition hover:bg-cyan-300">
                            Ir al Sistema
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-4 py-2 text-slate-100 transition hover:border-cyan-300 hover:text-cyan-200">
                            Iniciar sesión
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-full bg-white px-4 py-2 text-slate-950 transition hover:bg-slate-200">
                                Crear cuenta
                            </a>
                        @endif
                    @endauth
                </nav>
            </header>

            <main class="flex flex-1 items-center py-12 lg:py-16">
                <div class="grid gap-10 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)] lg:items-center">
                    <section>
                        <span class="inline-flex rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1 text-sm text-cyan-200">
                            CRM operativo para equipos comerciales
                        </span>

                        <h2 class="mt-6 max-w-3xl text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                            Centraliza contactos, campanas y administracion en una sola plataforma.
                        </h2>

                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                            AbiSystem organiza el trabajo diario de tu equipo con acceso rapido al panel, gestion de campañas, seguimiento de contactos y configuracion del sistema.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ route('menu') }}" class="rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                                    Abrir menu verificado
                                </a>
                                <a href="{{ route('profile') }}" class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:border-cyan-300 hover:text-cyan-200">
                                    Ver perfil
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-full bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300">
                                    Entrar al sistema
                                </a>
                            @endauth
                        </div>
                    </section>

                    <aside class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-cyan-950/30 backdrop-blur">
                        <h3 class="text-lg font-semibold text-white">Resumen de plataforma</h3>
                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                                <p class="text-sm font-medium text-cyan-200">Base de contactos</p>
                                <p class="mt-2 text-sm text-slate-300">Administra continentes, telefonos moviles y correos desde un flujo unico de consulta y seguimiento.</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                                <p class="text-sm font-medium text-cyan-200">Campañas y alcance</p>
                                <p class="mt-2 text-sm text-slate-300">Consulta campañas, exporta informacion clave y manten visibilidad sobre la ejecucion comercial.</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                                <p class="text-sm font-medium text-cyan-200">Administracion</p>
                                <p class="mt-2 text-sm text-slate-300">
                                    @auth
                                        Tu sesion ya esta activa. Entra al panel para continuar con la operacion del dia.
                                    @else
                                        Inicia sesion para acceder a los modulos protegidos y a la configuracion interna.
                                    @endauth
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</x-guest-layout>
