@props([
    'loginHref' => '/login',
    'signupHref' => '/cadastro',
])

<section {{ $attributes->merge([
    'class' => 'relative h-screen min-h-[640px] w-full overflow-hidden bg-arena-black font-montaser text-arena-ice',
]) }}>
    <div aria-hidden="true" class="absolute inset-y-0 right-0 w-1/2 bg-arena-purple"></div>

    <div class="absolute left-6 top-12 md:left-[60px]">
        <div class="h-[5px] w-[90px] bg-arena-lilac"></div>
        <div class="mt-[7px] h-[5px] w-[58px] bg-arena-violet"></div>
        <p class="mt-5 text-xs leading-[1.9] tracking-[0.3em] text-arena-lilac">
            INOVAÇÃO<br>
            CÓDIGO &amp; DADOS<br>
            FUTURO DIGITAL
        </p>
    </div>

    <nav class="absolute right-6 top-12 z-20 flex gap-3.5 md:right-16">
        <a
            href="{{ $loginHref }}"
            class="inline-flex h-[46px] items-center rounded-full border border-arena-lilac px-7 text-sm tracking-[0.08em] text-arena-ice shadow-[0_0_14px_rgba(181,140,255,0.45),inset_0_0_8px_rgba(181,140,255,0.18)] transition-shadow hover:shadow-[0_0_24px_rgba(181,140,255,0.7),inset_0_0_10px_rgba(181,140,255,0.25)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-arena-lilac"
        >
            Entrar
        </a>
        <a
            href="{{ $signupHref }}"
            class="inline-flex h-[46px] items-center rounded-full border border-arena-violet-light bg-arena-violet px-7 text-sm tracking-[0.08em] text-white shadow-[0_0_18px_rgba(167,139,250,0.6),inset_0_0_8px_rgba(255,255,255,0.15)] transition-shadow hover:shadow-[0_0_28px_rgba(167,139,250,0.85),inset_0_0_10px_rgba(255,255,255,0.2)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-arena-lilac"
        >
            Cadastrar-se
        </a>
    </nav>

    <div class="absolute inset-x-0 top-[6%] select-none text-center font-display text-[clamp(5rem,10.4vw,9.5rem)] font-medium leading-none tracking-[-0.02em]">
        <h1 class="m-0 text-arena-lilac [clip-path:inset(0_50%_0_0)]">Ars</h1>
        <span aria-hidden="true" class="absolute inset-0 text-arena-ice [clip-path:inset(0_0_0_50%)]">
            Ars
        </span>
    </div>

    <p class="absolute inset-x-0 top-[31%] flex text-xs tracking-[0.5em]">
        <span class="w-1/2 pr-[30px] text-right text-arena-lilac">TÉCNICA</span>
        <span class="w-1/2 pl-[30px] text-arena-ice">DISCIPLINA</span>
    </p>

    <img
        src="{{ asset('images/gladiador.png') }}"
        alt="Gladiador de capacete preto"
        class="pointer-events-none absolute left-1/2 top-[2%] h-[122%] w-auto max-w-none -translate-x-[47.8%] [mask-image:linear-gradient(to_right,transparent,#000_9%,#000_91%,transparent)]"
    >

    <p class="absolute right-6 top-[62%] text-right text-xs leading-[1.9] tracking-[0.3em] text-arena-ice md:right-16">
        ARENA<br>
        DE ESTUDO<br>
        DESDE 73 d.C.
    </p>
    <p class="absolute bottom-[10%] left-6 text-xs leading-[1.9] tracking-[0.3em] text-arena-ice md:left-[60px]">
        FORÇA<br>
        HONRA<br>
        GLÓRIA
    </p>

    <svg
        aria-hidden="true"
        viewBox="0 0 60 90"
        class="absolute bottom-12 right-6 h-[78px] w-[52px] md:right-16"
    >
        <path d="M30 4 L38 20 L36 60 L24 60 L22 20 Z" fill="#F1E8FB"></path>
        <rect x="8" y="60" width="44" height="6" rx="2" fill="#F1E8FB"></rect>
        <rect x="27" y="66" width="6" height="14" fill="#F1E8FB"></rect>
        <circle cx="30" cy="84" r="4" fill="#F1E8FB"></circle>
    </svg>
</section>
