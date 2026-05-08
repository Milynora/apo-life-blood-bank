<nav class="guest-nav" id="guest-nav">
  @php
    $isAuthPage = request()->routeIs([
  'login',
  'register',
  'password.request'
]);
  @endphp

  {{-- Logo --}}
  <a href="{{ route('home') }}" class="nav-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Apo Life Logo"/>
    <div class="nav-logo-text">
      Apo Life
    </div>
  </a>

  {{-- Desktop nav links --}}
  @unless($isAuthPage)
    <ul class="nav-links" id="desktop-nav">
      @foreach([
        ['#home', 'Home', 'home'],
        ['#about', 'About', 'about'],
        ['#how-it-works', 'How It Works', 'how-it-works'],
        ['#blood-types', 'Blood Types', 'blood-types'],
        ['#contact', 'Contact', 'contact'],
      ] as [$href, $label, $id])
        <li>
          <a href="{{ route('home') }}{{ $href }}"
             class="guest-nav-link"
             data-section="{{ $id }}">
            {{ $label }}
          </a>
        </li>
      @endforeach
    </ul>
  @endunless

  {{-- Auth buttons --}}
  <div style="display:flex; align-items:center; gap:0.6rem;">

    @guest
      @if($isAuthPage)
        {{-- Back to Home --}}
        <a href="{{ route('home') }}" class="btn btn-nav-register">
          Back to Home
        </a>
      @else
        @unless(request()->routeIs('login'))
          <a href="{{ route('login') }}" class="btn btn-nav-login">
            Login
          </a>
        @endunless

        @unless(request()->routeIs('register'))
          <a href="{{ route('register') }}" class="btn btn-nav-register">
            Register
          </a>
        @endunless
      @endif
    @endguest

    @auth
      @php
        $role = auth()->user()->role->value;
        $dashRoute = match($role) {
          'admin' => 'admin.dashboard',
          'staff' => 'staff.dashboard',
          'donor' => 'donor.dashboard',
          'hospital' => 'hospital.dashboard',
          default => 'home',
        };
      @endphp

      <a href="{{ route($dashRoute) }}" class="btn btn-nav-register">
        Dashboard
      </a>
    @endauth

    {{-- Mobile toggle --}}
    <button id="guest-mobile-toggle"
      style="display:none; background:none; border:none; color:rgba(255,255,255,0.8); cursor:pointer; padding:0.4rem;"
      aria-label="Menu">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" d="M3 6h18M3 12h18M3 18h18"/>
      </svg>
    </button>

  </div>
</nav>

{{-- Mobile menu --}}
<div id="guest-mobile-menu"
  style="display:none; position:fixed; top:68px; left:0; right:0; z-index:99;
         background:rgba(10,10,20,0.97); backdrop-filter:blur(20px);
         border-bottom:1px solid rgba(255,255,255,0.08); padding:1.5rem;">

  @unless($isAuthPage)
    <ul style="list-style:none; display:flex; flex-direction:column; gap:0.25rem; margin-bottom:1.25rem;">
      @foreach([
        ['#home','Home'],['#about','About'],['#how-it-works','How It Works'],
        ['#blood-types','Blood Types'],['#contact','Contact'],
      ] as [$href,$label])
        <li>
          <a href="{{ route('home') }}{{ $href }}"
            style="display:block; color:rgba(240,240,245,0.85); text-decoration:none; padding:0.7rem 1rem; border-radius:8px; font-size:0.95rem;">
            {{ $label }}
          </a>
        </li>
      @endforeach
    </ul>
  @endunless

  @guest
    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
      @if($isAuthPage)
        <a href="{{ route('home') }}" class="btn btn-primary" style="flex:1; text-align:center;">
          Back to Home
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline" style="flex:1; text-align:center;">
          Login
        </a>
        <a href="{{ route('register') }}" class="btn btn-primary" style="flex:1; text-align:center;">
          Register
        </a>
      @endif
    </div>
  @endguest

</div>

{{-- Styles --}}
<style>
/* Active nav link indicator */
.guest-nav-link {
  position: relative;
  color: rgba(240,240,245,0.8);
  text-decoration: none;
  font-size: 0.88rem;
  font-weight: 500;
  padding: 0.5rem 0.85rem;
  border-radius: var(--radius-sm);
  transition: color 0.2s;
}
.guest-nav-link::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 50%;
  transform: translateX(-50%) scaleX(0);
  width: 60%;
  height: 2px;
  background: linear-gradient(90deg, #e94560, #ff8fa3);
  border-radius: 2px;
  transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.guest-nav-link:hover { color: #fff; }
.guest-nav-link:hover::after { transform: translateX(-50%) scaleX(0); }
.guest-nav-link.active { color: #fff; }
.guest-nav-link.active::after { transform: translateX(-50%) scaleX(1); }

@media (max-width: 1024px) {
  #guest-mobile-toggle { display:block !important; }
}
</style>

{{-- Script --}}
<script>
  // Mobile toggle
  document.getElementById('guest-mobile-toggle')?.addEventListener('click', function() {
    const menu = document.getElementById('guest-mobile-menu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
  });

  // Smooth scroll + close menu
  document.querySelectorAll('a[href*="#"]').forEach(link => {
    link.addEventListener('click', function(e) {
      const hash = this.getAttribute('href');
      if (hash.includes('#')) {
        const target = document.querySelector(hash.split('#')[1] ? '#' + hash.split('#')[1] : null);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth' });
          history.pushState(null, null, hash);
        }
      }
      document.getElementById('guest-mobile-menu').style.display = 'none';
    });
  });

  // Active section detection via IntersectionObserver
  document.addEventListener('DOMContentLoaded', function() {
    const sections = ['home','about','how-it-works','blood-types','contact'];
    const links    = document.querySelectorAll('.guest-nav-link');

    function setActive(id) {
      links.forEach(l => {
        if (l.dataset.section === id) l.classList.add('active');
        else l.classList.remove('active');
      });
    }

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) setActive(entry.target.id);
        });
      }, { threshold: 0.35, rootMargin: '-64px 0px -30% 0px' });

      sections.forEach(id => {
        const el = document.getElementById(id);
        if (el) observer.observe(el);
      });
    }

    // Smooth hash scroll
    links.forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href.includes('#')) {
          const hash = '#' + href.split('#')[1];
          const target = document.querySelector(hash);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
            history.pushState(null, null, hash);
          }
        }
        // Close mobile menu
        document.getElementById('guest-mobile-menu').style.display = 'none';
      });
    });
  });
</script>
