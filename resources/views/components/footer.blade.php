<footer class="footer">
  <div class="container">
    <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap:3rem; margin-bottom:3rem;">

      {{-- Brand column --}}
      <div>
        <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:10px; text-decoration:none; margin-bottom:1.25rem;">
          <img src="{{ asset('images/logo.png') }}" alt="Apo Life" style="height:40px; width:auto;"/>
          <div>
            <div style="font-family:var(--font-display); font-size:1.1rem; font-weight:700; color:#fff;">Apo Life</div>
            <div style="font-size:0.7rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em;">Davao City, Philippines</div>
          </div>
        </a>
        <p style="font-size:0.875rem; line-height:1.7; color:var(--text-muted); max-width:280px;">
          Committed to saving lives through a safe, accessible, and efficient blood donation management system serving Davao City and beyond.
        </p>
        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
          {{-- Social icons --}}
          @foreach([
            ['fb',   'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
            ['tw',   'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
            ['ig',   'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01'],
          ] as [$net, $path])
            <a href="#" style="width:36px; height:36px; border-radius:8px; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color:var(--text-muted); transition:all 0.2s; text-decoration:none;"
               onmouseover="this.style.background='rgba(233,69,96,0.2)'; this.style.color='#e94560'; this.style.borderColor='rgba(233,69,96,0.4)'"
               onmouseout="this.style.background='rgba(255,255,255,0.07)'; this.style.color='var(--text-muted)'; this.style.borderColor='rgba(255,255,255,0.1)'">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
              </svg>
            </a>
          @endforeach
        </div>
      </div>

      {{-- Quick Links --}}
      <div>
        <h4 class="footer-title">Quick Links</h4>
        <a href="{{ route('home') }}#home"         class="footer-link">Home</a>
        <a href="{{ route('home') }}#about"        class="footer-link">About Us</a>
        <a href="{{ route('home') }}#how-it-works" class="footer-link">How It Works</a>
        <a href="{{ route('home') }}#blood-types"  class="footer-link">Blood Types</a>
        <a href="{{ route('home') }}#contact"      class="footer-link">Contact Us</a>
      </div>

      {{-- For Donors --}}
      <div>
        <h4 class="footer-title">For Donors</h4>
        <a href="{{ route('register') }}" class="footer-link">Register as Donor</a>
        <a href="{{ route('login') }}"    class="footer-link">Donor Login</a>
        <a href="{{ route('home') }}#eligibility" class="footer-link">Eligibility Check</a>
        <a href="{{ route('home') }}#faq"         class="footer-link">FAQs</a>
      </div>

      {{-- Contact --}}
      <div>
        <h4 class="footer-title">Contact Us</h4>
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
          @foreach([
            ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'J.P. Laurel Ave, Bajada,<br>Davao City 8000, Philippines'],
            ['M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', '+63 (82) 123-4567'],
            ['M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'apolife.org.ph'],
            ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'Mon–Sat: 7:00AM – 5:00PM'],
          ] as [$icon, $text])
            <div style="display:flex; gap:0.75rem; align-items:flex-start;">
              <svg width="15" height="15" fill="none" stroke="var(--accent-pink)" stroke-width="1.8" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                <path stroke-linecap="round" d="{{ $icon }}"/>
              </svg>
              <span style="font-size:0.82rem; color:var(--text-muted); line-height:1.5;">{!! $text !!}</span>
            </div>
          @endforeach
        </div>
      </div>

    </div>

    {{-- Bottom bar --}}
    <div class="footer-bottom">
      <span>&copy; {{ date('Y') }} Apo Life. All rights reserved.</span>
      <div style="display:flex; gap:1.5rem;">
        <a href="#" class="footer-link" style="padding:0;">Privacy Policy</a>
        <a href="#" class="footer-link" style="padding:0;">Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<style>
  @media (max-width: 900px) {
    .footer > .container > div:first-child {
      grid-template-columns: 1fr 1fr !important;
    }
  }
  @media (max-width: 600px) {
    .footer > .container > div:first-child {
      grid-template-columns: 1fr !important;
    }
  }
</style>