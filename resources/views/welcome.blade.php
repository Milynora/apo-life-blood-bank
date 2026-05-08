<x-guest-layout title="Welcome">

  @push('particles')
    <div id="particles-js" style="position:fixed; inset:0; z-index:1; pointer-events:none;"></div>
  @endpush

  {{-- ═══════════════════════════════════════
       HERO SECTION
  ═══════════════════════════════════════ --}}
  <section class="hero" id="home">
    {{-- Background image overlay --}}
    <div style="position:absolute; inset:0; z-index:0;">
  
  <!-- Image -->
  <img src="{{ asset('images/hero.jpeg') }}" 
       style="width:100%; height:100%; object-fit:cover; opacity:0.35;" />

  <!-- Overlay -->
  <div style="position:absolute; inset:0; 
       background:linear-gradient(135deg, 
         rgba(26,26,46,0.6) 0%, 
         rgba(15,52,96,0.5) 50%, 
         rgba(83,52,131,0.45) 100%);
  "></div>

</div>

    <div class="container" style="position:relative; z-index:2; display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center; padding-top:0.5rem; padding-bottom:1rem;">

      {{-- Left: Content --}}
      <div class="hero-content">
        <div class="hero-badge">
          Saving Lives Since 2026
        </div>

        <h1 class="hero-title">
          Every Drop<br>
          <span class="highlight" id="typed-text"></span>
        </h1>

        <p class="hero-desc">
          Apo Life connects generous donors with those in critical need.
          Your single donation can save up to three lives. Join our community of heroes in Davao City.
        </p>

        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
          <a href="{{ route('register') }}?role=donor" class="btn btn-primary btn-lg">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Donate Blood
          </a>
          <a href="{{ route('register') }}?role=hospital" class="btn btn-outline btn-lg">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Request Blood
          </a>
        </div>

        
      </div>

      {{-- Right: Visual --}}
<div style="display:flex; justify-content:center; align-items:center;">
  <div class="pulse-card" style="position:relative; width:100%; max-width:460px;">

    {{-- Main glass card --}}
    <div class="glass" style="padding:0rem; border-radius:28px; overflow:hidden;">
      
      <img 
        src="/images/blood-drive.png" 
        alt="Blood Drive"
        style="
          width:100%;
          height:auto;
          display:block;
          border-radius:20px;
        "
      >

    </div>
  </div>
</div>

    </div>
  </section>

  {{-- ═══════════════════════════════════════
       ABOUT
  ═══════════════════════════════════════ --}}
  <section class="section" id="about" style="background:linear-gradient(180deg,#0f3460 0%,#1a1a2e 100%);">
    <div class="container">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center;">

        <div data-aos="fade-right">
          <div style="position:relative; border-radius:24px; overflow:hidden;">
            <img src="{{ asset('images/background.jpg') }}" alt="Apo Life" style="width:100%; height:500px; object-fit:cover; border-radius:24px; display:block;"/>
            <div style="position:absolute; bottom:1.5rem; left:1.5rem; right:1.5rem;">
            </div>
          </div>
        </div>

        <div data-aos="fade-left">
          <div class="section-label">About Us</div>
          <h2 class="section-title light">Apo Life</h2>
          <p class="section-desc light" style="margin-top:1rem;">
            Established in Davao City, Apo Life is a community-driven blood bank dedicated to ensuring safe and adequate blood supply for hospitals and patients in the Davao Region.
          </p>
          <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.8; margin-top:1rem;">
            Our state-of-the-art facility on J.P. Laurel Avenue ensures that every blood unit is carefully screened, processed, and stored under strict medical standards. We partner with major hospitals across Davao City to ensure timely delivery of blood products to patients in critical need.
          </p>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-top:2rem;">
            @foreach([
              ['1,200+', 'Active Donors'],
              ['38',     'Partner Hospitals'],
              ['5,600+', 'Units Stored'],
              ['99.8%',  'Safety Record'],
            ] as [$val, $lbl])
              <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:16px; padding:1.25rem; text-align:center;">
                <div style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; color:#e94560; line-height:1;">{{ $val }}</div>
                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.35rem; text-transform:uppercase; letter-spacing:0.05em;">{{ $lbl }}</div>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  </section>
  
  {{-- ═══════════════════════════════════════
       WHY DONATE?
  ═══════════════════════════════════════ --}}
  <section class="section" style="background:linear-gradient(180deg, #0f3460 0%, #1a1a2e 100%);">
    <div class="container">
      <div style="text-align:center; margin-bottom:3rem;" data-aos="fade-up">
        <div class="section-label" style="justify-content:center;">Why Donate?</div>
        <h2 class="section-title light">One Donation. Three Lives.</h2>
        <p class="section-desc light" style="margin:1rem auto 0; text-align:center;">
        Blood cannot be manufactured—it only comes from donors like you.        </p>
      </div>

      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem;">
        @foreach([
          ['M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'Save 3 Lives', 'One blood donation can be separated into red cells, plasma, and platelets — saving up to three different patients.', 100],
          ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'Safe & Screened', 'All blood is thoroughly tested and screened. Your safety and recipient safety are our top priorities.', 200],
          ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'Only 15 Minutes', 'The entire donation process takes less than an hour. The actual blood draw is only about 10-15 minutes.', 300],
        ] as [$icon, $title, $desc, $delay])
          <div class="glass feature-card" data-aos="fade-up" data-aos-delay="{{ $delay }}">
            <div style="width:56px; height:56px; background:linear-gradient(135deg, rgba(233,69,96,0.2), rgba(192,57,43,0.1)); border:1px solid rgba(233,69,96,0.3); border-radius:16px; display:flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
              <svg width="26" height="26" fill="none" stroke="#e94560" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="{{ $icon }}"/>
              </svg>
            </div>
            <h3 style="font-family:var(--font-display); font-size:1.2rem; font-weight:700; color:#fff; margin-bottom:0.75rem;">{{ $title }}</h3>
            <p style="color:var(--text-muted); font-size:0.875rem; line-height:1.7;">{{ $desc }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════
       HOW IT WORKS
  ═══════════════════════════════════════ --}}
  <section class="section" id="how-it-works" style="background:#16213e;">
    <div class="container">
      <div style="text-align:center; margin-bottom:3.5rem;" data-aos="fade-up">
        <div class="section-label" style="justify-content:center;">Process</div>
        <h2 class="section-title light">How It Works</h2>
      </div>

      <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1rem; position:relative;">
        {{-- Connecting line --}}
        <div style="position:absolute; top:24px; left:10%; right:10%; height:2px; background:linear-gradient(90deg, #e94560, #533483); z-index:0; opacity:0.4;"></div>

        @foreach([
          ['1', 'Register', 'Create your donor account with your personal details and blood type.', 100],
          ['2', 'Schedule', 'Book an appointment at your preferred date and time.', 200],
          ['3', 'Screening', 'Undergo a quick health screening by our trained staff.', 300],
          ['4', 'Donate',   'Donate blood — the actual process takes only 10–15 minutes.', 400],
          ['5', 'Save Lives', 'Your blood is processed and matched to patients in need.', 500],
        ] as [$num, $title, $desc, $delay])
          <div style="display:flex; flex-direction:column; align-items:center; text-align:center; position:relative; z-index:1;" data-aos="fade-up" data-aos-delay="{{ $delay }}">
            <div class="step-number" style="margin-bottom:1rem;">{{ $num }}</div>
            <h3 style="font-size:0.95rem; font-weight:700; color:#fff; margin-bottom:0.5rem;">{{ $title }}</h3>
            <p style="font-size:0.78rem; color:var(--text-muted); line-height:1.6;">{{ $desc }}</p>
          </div>
        @endforeach
      </div>

      <div style="text-align:center; margin-top:3rem;" data-aos="fade-up">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Your Journey</a>
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════
       BLOOD TYPES
  ═══════════════════════════════════════ --}}
  <section class="section" id="blood-types" style="background:linear-gradient(180deg, #1a1a2e 0%, #0f3460 100%);">
    <div class="container">
      <div style="text-align:center; margin-bottom:3rem;" data-aos="fade-up">
        <div class="section-label" style="justify-content:center;">Compatibility</div>
        <h2 class="section-title light">Know Your Blood Type</h2>
        <p class="section-desc light" style="margin:1rem auto 0; text-align:center;">Hover over each card to see donation compatibility.</p>
      </div>

      <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem;">
        @foreach([
          ['A+',  '#3498DB', 'A+, A-, O+, O-', 'A+, AB+'],
          ['A-',  '#2980B9', 'A-, O-',          'A+, A-, AB+, AB-'],
          ['B+',  '#9B59B6', 'B+, B-, O+, O-', 'B+, AB+'],
          ['B-',  '#8E44AD', 'B-, O-',          'B+, B-, AB+, AB-'],
          ['AB+', '#1ABC9C', 'All types',        'AB+ only'],
          ['AB-', '#16A085', 'AB-, A-, B-, O-', 'AB+, AB-'],
          ['O+',  '#E74C3C', 'O+, O-',          'A+, B+, O+, AB+'],
          ['O-',  '#C0392B', 'O- only',          'Everyone (Universal)'],
        ] as [$type, $color, $from, $to])
          <div class="blood-type-card glass"
            data-aos="fade-up"
            data-aos-delay="{{ $loop->index * 50 }}"
            style="border-color:{{ $color }}33; padding:1.5rem; cursor:default;"
            onmouseover="this.style.borderColor='{{ $color }}88'; this.querySelector('.bt-back').style.opacity='1'; this.querySelector('.bt-front').style.opacity='0';"
            onmouseout="this.style.borderColor='{{ $color }}33'; this.querySelector('.bt-back').style.opacity='0'; this.querySelector('.bt-front').style.opacity='1';">

            {{-- Front --}}
            <div class="bt-front" style="transition:opacity 0.3s; text-align:center;">
              <div style="font-family:var(--font-mono); font-size:2.5rem; font-weight:800; color:{{ $color }}; line-height:1; margin-bottom:0.5rem; text-shadow:0 0 20px {{ $color }}44;">{{ $type }}</div>
              <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em;">Blood Type</div>
            </div>

            {{-- Back (hover) --}}
            <div class="bt-back" style="position:absolute; inset:0; padding:1.25rem; opacity:0; transition:opacity 0.3s; display:flex; flex-direction:column; justify-content:center;">
              <div style="font-size:0.72rem; color:var(--text-muted); margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em;">Accepts from:</div>
              <div style="font-size:0.8rem; color:#fff; margin-bottom:0.75rem;">{{ $from }}</div>
              <div style="font-size:0.72rem; color:var(--text-muted); margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em;">Can donate to:</div>
              <div style="font-size:0.8rem; color:#fff;">{{ $to }}</div>
            </div>

          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════
       ELIGIBILITY
  ═══════════════════════════════════════ --}}
  <section class="section" id="eligibility" style="background:#16213e;">
    <div class="container-sm">
      <div style="text-align:center; margin-bottom:3rem;" data-aos="fade-up">
        <div class="section-label" style="justify-content:center;">Who Can Donate?</div>
        <h2 class="section-title light">Eligibility Criteria</h2>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:3rem; align-items:center;">
        <div data-aos="fade-right">
          <div style="display:flex; flex-direction:column; gap:1rem;">
            @foreach([
              [true,  'Age 18 to 65 years old'],
              [true,  'Weight at least 50 kg (110 lbs)'],
              [true,  'Hemoglobin level ≥ 12.5 g/dL'],
              [true,  'No fever or active infection'],
              [true,  'At least 56 days since last donation'],
              [false, 'Currently pregnant or just gave birth'],
              [false, 'Recent tattoo or piercing (12 months)'],
              [false, 'Taking certain medications'],
            ] as [$ok, $item])
              <div style="display:flex; align-items:center; gap:1rem; padding:0.85rem 1.1rem; border-radius:12px; background:{{ $ok ? 'rgba(39,174,96,0.08)' : 'rgba(192,57,43,0.08)' }}; border:1px solid {{ $ok ? 'rgba(39,174,96,0.2)' : 'rgba(192,57,43,0.2)' }};">
                <div style="width:28px; height:28px; border-radius:50%; background:{{ $ok ? 'rgba(39,174,96,0.2)' : 'rgba(192,57,43,0.15)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <svg width="14" height="14" fill="none" stroke="{{ $ok ? '#27AE60' : '#E74C3C' }}" stroke-width="2.5" viewBox="0 0 24 24">
                    @if($ok)
                      <path stroke-linecap="round" d="M5 13l4 4L19 7"/>
                    @else
                      <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                    @endif
                  </svg>
                </div>
                <span style="font-size:0.875rem; color:{{ $ok ? '#a0d8b0' : '#e8a0a0' }};">{{ $item }}</span>
              </div>
            @endforeach
          </div>
        </div>

        <div data-aos="fade-left">
          <div class="glass" style="padding:2rem; border-radius:24px;">
            <div style="font-family:var(--font-display); font-size:1.3rem; font-weight:700; color:#fff; margin-bottom:1rem;">
              Not sure if you qualify?
            </div>
            <p style="color:var(--text-muted); font-size:0.875rem; line-height:1.7; margin-bottom:1.5rem;">
              Our trained medical staff will conduct a pre-donation screening to ensure you're fit to donate. Your health and safety are our priority.
            </p>
            <div style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:1.75rem;">
              @foreach(['Register your account', 'Schedule an appointment', 'Come in for free screening'] as $step)
                <div style="display:flex; align-items:center; gap:0.85rem;">
                  <div style="width:24px; height:24px; background:linear-gradient(135deg,#e94560,#922B21); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                  <span style="font-size:0.875rem; color:rgba(240,240,245,0.85);">{{ $step }}</span>
                </div>
              @endforeach
            </div>
            <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%; justify-content:center;">Register Now — It's Free</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════
       TESTIMONIALS
  ═══════════════════════════════════════ --}}
  <section class="section" style="background:#16213e; overflow:hidden;">
    <div class="container">
      <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:2.5rem; flex-wrap:wrap; gap:1rem;" data-aos="fade-up">
        <div>
          <div class="section-label">Stories</div>
          <h2 class="section-title light">From Our Community</h2>
        </div>
        {{-- Arrow controls --}}
        <div style="display:flex; gap:0.6rem;">
          <button id="testi-prev" class="testi-arrow" aria-label="Previous">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
          </button>
          <button id="testi-next" class="testi-arrow" aria-label="Next">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      {{-- Carousel track --}}
      <div class="testi-viewport" id="testi-viewport">
        <div class="testi-track" id="testi-track">
          @foreach([
            ['Maria Santos',       'Donor since 2026',
             'I never thought a simple act could mean so much. Knowing that my blood saved a child during surgery fills my heart with joy. I donate every 2 months now.',
             '#e94560'],
            ['Dr. James Reyes',    'Southern Philippines Medical Center',
             'Apo Life has been our most reliable partner. Their response time during emergencies is exceptional, and blood quality is always consistently safe.',
             '#3498DB'],
            ['Ana Dela Cruz',      'Recipient family',
             'My husband needed 8 units of blood during surgery. Apo Life ensured everything was available. We are forever grateful to the donors.',
             '#27AE60'],
            ['Michael Fernandez',  'Donor since 2026',
             'I started donating after my sister needed blood and we struggled to find it. Now I encourage everyone I know to register. It costs nothing but saves everything.',
             '#9B59B6'],
            ['Dr. Liza Navarro',   'Davao Medical Center',
             'Having a reliable blood bank partner like Apo Life has saved countless lives at our hospital. Their system makes requesting blood units fast and efficient.',
             '#1ABC9C'],
          ] as [$name, $role, $quote, $accent])
            <div class="testi-slide">
              <div class="testi-card">
                {{-- Quote mark --}}
                <div style="font-size:3.5rem; line-height:0.8; color:{{ $accent }}; opacity:0.45; margin-bottom:1rem; font-family:Georgia,serif; font-weight:900;">"</div>
                <p style="color:rgba(240,240,245,0.88); font-size:0.9rem; line-height:1.85; margin-bottom:1.5rem; font-style:italic; flex:1;">{{ $quote }}</p>
                {{-- Divider --}}
                <div style="height:1px; background:linear-gradient(90deg, {{ $accent }}44, transparent); margin-bottom:1.25rem;"></div>
                {{-- Author --}}
                <div style="display:flex; align-items:center; gap:0.9rem;">
                  <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,{{ $accent }},{{ $accent }}88); display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1rem; font-weight:800; color:#fff; flex-shrink:0; box-shadow:0 4px 12px {{ $accent }}44;">
                    {{ strtoupper(substr($name,0,1)) }}
                  </div>
                  <div>
                    <div style="font-weight:700; font-size:0.88rem; color:#fff; margin-bottom:0.15rem;">{{ $name }}</div>
                    <div style="font-size:0.73rem; color:{{ $accent }}; font-weight:500;">{{ $role }}</div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Dot indicators --}}
      <div id="testi-dots" style="display:flex; justify-content:center; gap:0.5rem; margin-top:2rem;"></div>

    </div>

    <style>
      .testi-arrow {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
        border: 1.5px solid rgba(255,255,255,0.18);
        color: rgba(240,240,245,0.8);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
      }
      .testi-arrow:hover {
        background: rgba(233,69,96,0.18);
        border-color: rgba(233,69,96,0.45);
        color: #e94560;
      }
      .testi-arrow:disabled { opacity: 0.3; cursor: not-allowed; }

      .testi-viewport {
        overflow: hidden;
        width: 100%;
        -webkit-user-select: none;
        user-select: none;
      }
      .testi-track {
        display: flex;
        gap: 1.25rem;
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
      }
      .testi-slide {
        flex: 0 0 calc(33.333% - 0.85rem);
        min-width: 0;
      }
      @media (max-width: 1024px) {
        .testi-slide { flex: 0 0 calc(50% - 0.65rem); }
      }
      @media (max-width: 640px) {
        .testi-slide { flex: 0 0 calc(90% - 0rem); }
      }
      .testi-card {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 1.75rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(16px);
        transition: all 0.3s;
      }
      .testi-card:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.2);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
      }
      .testi-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.25);
        border: none; cursor: pointer;
        transition: all 0.2s;
        padding: 0;
      }
      .testi-dot.active {
        background: #e94560;
        width: 22px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(233,69,96,0.5);
      }
    </style>
  </section>

  {{-- ═══════════════════════════════════════
       CTA BANNER
  ═══════════════════════════════════════ --}}
  <section class="section-sm" style="background:linear-gradient(135deg, #922B21 0%, #e94560 50%, #533483 100%);">
    <div class="container" style="text-align:center;" data-aos="fade-up">
      <h2 style="font-family:var(--font-display); font-size:2.2rem; font-weight:800; color:#fff; margin-bottom:1rem; letter-spacing:-0.02em;">
        Ready to Save a Life?
      </h2>
      <p style="color:rgba(255,255,255,0.85); font-size:1rem; margin-bottom:2rem; max-width:520px; margin-left:auto; margin-right:auto; line-height:1.7;">
        Join over 1,200 donors in Davao City who are making a difference every day. Registration is free and takes less than 5 minutes.
      </p>
      <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
        <a href="{{ route('register') }}?role=donor"    class="btn btn-lg" style="background:rgba(255,255,255,0.15); color:#fff; border:2px solid rgba(255,255,255,0.4); backdrop-filter:blur(10px);">
          Register as Donor
        </a>
        <a href="{{ route('register') }}?role=hospital" class="btn btn-lg" style="background:#fff; color:#922B21; font-weight:700;">
          Register as Hospital
        </a>
      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════
       CONTACT
  ═══════════════════════════════════════ --}}
  <section class="section" id="contact" style="background:#1a1a2e;">
    <div class="container">
      <div style="text-align:center; margin-bottom:3rem;" data-aos="fade-up">
        <div class="section-label" style="justify-content:center;">Get In Touch</div>
        <h2 class="section-title light">Contact Us</h2>
      </div>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:3rem;">

        {{-- Contact info --}}
        <div data-aos="fade-right">
          <div class="glass" style="padding:2rem; border-radius:24px;">
            <div style="display:flex; flex-direction:column; gap:1.5rem;">
              @foreach([
                ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'Address', 'J.P. Laurel Ave, Bajada<br>Davao City 8000, Davao del Sur<br>Philippines'],
                ['M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'Phone', '+63 (82) 123-4567<br>+63 917 123 4567'],
                ['M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Email', 'apolife.org.ph<br>emergency@apolife.org.ph'],
                ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'Hours', 'Monday – Saturday: 7:00 AM – 5:00 PM<br>Emergency: 24 hours'],
              ] as [$icon, $label, $value])
                <div style="display:flex; gap:1rem; align-items:flex-start;">
                  <div style="width:44px; height:44px; background:rgba(233,69,96,0.15); border:1px solid rgba(233,69,96,0.25); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" fill="none" stroke="#e94560" stroke-width="1.8" viewBox="0 0 24 24">
                      <path stroke-linecap="round" d="{{ $icon }}"/>
                    </svg>
                  </div>
                  <div>
                    <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:0.3rem;">{{ $label }}</div>
                    <div style="font-size:0.875rem; color:rgba(240,240,245,0.85); line-height:1.6;">{!! $value !!}</div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Quick links / CTA --}}
        <div data-aos="fade-left">
          <div class="glass" style="padding:2rem; border-radius:24px; height:100%; display:flex; flex-direction:column; justify-content:center;">
            <h3 style="font-family:var(--font-display); font-size:1.3rem; font-weight:700; color:#fff; margin-bottom:0.75rem;">Emergency Blood Request?</h3>
            <p style="color:var(--text-muted); font-size:0.875rem; line-height:1.7; margin-bottom:1.5rem;">
              For emergency blood requests, call our 24-hour hotline or have your hospital register and submit a request through our system for faster processing.
            </p>
            <div style="display:flex; flex-direction:column; gap:0.85rem;">
              <a href="tel:+6382 123 4567" class="btn btn-primary" style="justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Call Emergency Hotline
              </a>
              <a href="{{ route('register') }}?role=hospital" class="btn btn-glass" style="justify-content:center;">
                Register as Hospital
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
  <script>
    // Particles
    particlesJS('particles-js', {
      particles: {
        number: { value: 50, density: { enable: true, value_area: 900 } },
        color: { value: '#e94560' },
        shape: { type: 'circle' },
        opacity: { value: 0.3, random: true },
        size: { value: 3, random: true },
        line_linked: { enable: true, distance: 150, color: '#533483', opacity: 0.15, width: 1 },
        move: { enable: true, speed: 1.2, direction: 'none', random: true, out_mode: 'out' },
      },
      interactivity: {
        detect_on: 'canvas',
        events: { onhover: { enable: true, mode: 'grab' }, onclick: { enable: true, mode: 'push' } },
        modes: { grab: { distance: 140, line_linked: { opacity: 0.4 } }, push: { particles_nb: 2 } },
      },
    });

    // Typewriter
    new Typed('#typed-text', {
      strings: ['Counts.', 'Matters.', 'Saves Lives.'],
      typeSpeed: 70,
      backSpeed: 45,
      backDelay: 2000,
      loop: true,
    });

    // ── Testimonials carousel ─────────────────────────
    (function() {
      const track     = document.getElementById('testi-track');
      const viewport  = document.getElementById('testi-viewport');
      const dotsWrap  = document.getElementById('testi-dots');
      const btnPrev   = document.getElementById('testi-prev');
      const btnNext   = document.getElementById('testi-next');
      if (!track) return;

      const slides    = Array.from(track.querySelectorAll('.testi-slide'));
      let current     = 0;
      let autoTimer   = null;

      function getSlidesVisible() {
        const w = viewport.offsetWidth;
        if (w < 640)  return 1;
        if (w < 1024) return 2;
        return 3;
      }

      function maxIndex() {
        return Math.max(0, slides.length - getSlidesVisible());
      }

      // Build dots
      function buildDots() {
        dotsWrap.innerHTML = '';
        const pages = maxIndex() + 1;
        for (let i = 0; i < pages; i++) {
          const d = document.createElement('button');
          d.className = 'testi-dot' + (i === current ? ' active' : '');
          d.setAttribute('aria-label', 'Go to slide ' + (i+1));
          d.addEventListener('click', () => goTo(i));
          dotsWrap.appendChild(d);
        }
      }

      function updateDots() {
        dotsWrap.querySelectorAll('.testi-dot').forEach((d, i) => {
          d.classList.toggle('active', i === current);
        });
      }

      function getSlideWidth() {
        if (!slides[0]) return 0;
        const rect = slides[0].getBoundingClientRect();
        const gap  = 20; // 1.25rem gap
        return rect.width + gap;
      }

      function goTo(index) {
        current = Math.max(0, Math.min(index, maxIndex()));
        track.style.transform = `translateX(-${current * getSlideWidth()}px)`;
        btnPrev.disabled = current === 0;
        btnNext.disabled = current >= maxIndex();
        updateDots();
      }

      btnPrev.addEventListener('click', () => { goTo(current - 1); resetAuto(); });
      btnNext.addEventListener('click', () => { goTo(current + 1); resetAuto(); });

      // Touch / drag
      let startX = 0, dragging = false;
      track.addEventListener('pointerdown', e => { startX = e.clientX; dragging = true; track.setPointerCapture(e.pointerId); resetAuto(); });
      track.addEventListener('pointermove', e => { if (!dragging) return; });
      track.addEventListener('pointerup',   e => {
        if (!dragging) return; dragging = false;
        const diff = startX - e.clientX;
        if (Math.abs(diff) > 40) { diff > 0 ? goTo(current + 1) : goTo(current - 1); }
      });

      function startAuto() {
        autoTimer = setInterval(() => {
          goTo(current >= maxIndex() ? 0 : current + 1);
        }, 4500);
      }
      function resetAuto() { clearInterval(autoTimer); startAuto(); }

      // Init
      buildDots();
      goTo(0);
      startAuto();

      window.addEventListener('resize', () => {
        buildDots();
        goTo(Math.min(current, maxIndex()));
      });
    })();

    // Blood type card positioning fix
    document.querySelectorAll('.blood-type-card').forEach(card => {
      card.style.position = 'relative';
    });
  </script>
  @endpush

  <style>
    @keyframes dropPulse {
      0%,100% { transform: scale(1); filter: drop-shadow(0 0 20px rgba(233,69,96,0.4)); }
      50%      { transform: scale(1.04); filter: drop-shadow(0 0 35px rgba(233,69,96,0.7)); }
    }
    @keyframes bounce {
      0%,100% { transform: translateX(-50%) translateY(0); }
      50%      { transform: translateX(-50%) translateY(8px); }
    }
    .swiper-pagination-bullet { background: rgba(255,255,255,0.3) !important; }
    .swiper-pagination-bullet-active { background: #e94560 !important; }
    @media (max-width: 900px) {
      .hero > .container { grid-template-columns: 1fr !important; }
      .hero > .container > div:last-child { display: none; }
      .section > .container > div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(3,1fr) !important; }
      .section > .container > div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; }
      .section > .container > div[style*="grid-template-columns:repeat(3"] { grid-template-columns: 1fr !important; }
      .section > .container > div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }

    @keyframes softPulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 rgba(233, 69, 96, 0.0);
  }
  50% {
    transform: scale(1.015);
    box-shadow: 0 0 35px rgba(233, 69, 96, 0.25);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 rgba(233, 69, 96, 0.0);
  }
}

.pulse-card {
  animation: softPulse 3s ease-in-out infinite;
}
.stat-item { text-align: center; }
  </style>

</x-guest-layout>