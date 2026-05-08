<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>{{ $title ?? 'Apo Life' }} — Apo Life</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>

  {{-- Vite (Tailwind) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Custom CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>

  {{-- AOS --}}
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"/>

  {{-- Swiper --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

  {{-- Flatpickr --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"/>

  {{-- Extra head content --}}
  @stack('head')
</head>
<body class="guest-body">

  {{-- Animated mesh background --}}
  <div class="mesh-bg"></div>

  {{-- Particle container (only on welcome) --}}
  @stack('particles')

  {{-- Guest Navbar --}}
  @include('components.navbar-guest')

  {{-- Main Content --}}
  <main>
    {{ $slot }}
  </main>

  {{-- Footer --}}
  @include('components.footer')

  {{-- Scripts --}}
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.1.0/dist/typed.umd.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/countup.js@2.8.0/dist/countUp.umd.js"></script>

  {{-- Alpine is bundled via Breeze --}}

  <script>
    // Init AOS
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60,
    });

    // Navbar scroll effect
    const nav = document.getElementById('guest-nav');
    if (nav) {
      window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
      });
    }
  </script>

  @stack('scripts')
</body>
</html>
