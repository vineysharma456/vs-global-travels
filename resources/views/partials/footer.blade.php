{{-- ═══════════════════════════════════════════
     VizaFly — Footer Partial
     Include via: @include('partials.footer')
═══════════════════════════════════════════ --}}

<style>
  /* ── CSS Variables ─────────────────────── */
  :root {
    --brand-navy:      #0d2750;
    --brand-pink:      #cc00cc;
    --brand-pink-light:#e500e5;
    --footer-bg:       #f0f2f7;
    --footer-text:     #4a5568;
    --footer-heading:  #0d2750;
    --footer-link:     #5a6a82;
    --footer-link-hover: #cc00cc;
  }

  /* ── Footer Shell ──────────────────────── */
  .vizafly-footer {
    background: var(--footer-bg);
    border-top: 3px solid var(--brand-pink);
    padding: 3.5rem 0 0;
    font-family: 'DM Sans', 'Segoe UI', sans-serif;
  }

  /* ── Logo ──────────────────────────────── */
  .footer-logo-text {
    font-family: 'Nunito', 'Segoe UI', sans-serif;
    font-weight: 900;
    font-size: 1.75rem;
    color: var(--brand-navy);
    letter-spacing: -0.5px;
    line-height: 1;
    text-decoration: none;
  }
  .footer-logo-text .z-letter { color: var(--brand-pink); }
  .footer-logo-sub {
    display: block;
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    color: var(--brand-pink);
    text-transform: lowercase;
    margin-top: 2px;
    padding-left: 2px;
  }

  /* Suitcase icon */
  .footer-suitcase {
    display: inline-block;
    position: relative;
    width: 30px;
    height: 36px;
    margin-left: 5px;
    vertical-align: middle;
    top: -4px;
  }
  .footer-suitcase .fs-handle {
    position: absolute; top: 0; left: 50%;
    transform: translateX(-50%);
    width: 13px; height: 11px;
    border: 3px solid var(--brand-pink);
    border-bottom: none;
    border-radius: 4px 4px 0 0;
  }
  .footer-suitcase .fs-body {
    position: absolute; bottom: 0; left: 0;
    width: 30px; height: 27px;
    background: var(--brand-pink);
    border-radius: 5px;
  }
  .footer-suitcase .fs-stripe {
    position: absolute; bottom: 9px; left: 0;
    width: 100%; height: 3px;
    background: rgba(255,255,255,.35);
    border-radius: 2px;
  }
  .footer-suitcase .fs-wheel {
    position: absolute; bottom: 1px;
    width: 5px; height: 5px;
    background: #fff; border-radius: 50%;
  }
  .footer-suitcase .fs-wheel.l { left: 4px; }
  .footer-suitcase .fs-wheel.r { right: 4px; }

  /* ── Tagline ───────────────────────────── */
  .footer-tagline {
    color: var(--footer-text);
    font-size: 0.88rem;
    line-height: 1.65;
    margin-top: 1rem;
    max-width: 230px;
  }

  /* ── Social Icons ──────────────────────── */
  .footer-socials { margin-top: 1.4rem; display: flex; gap: 0.65rem; }
  .footer-socials a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--brand-pink);
    color: #fff;
    font-size: 1rem;
    text-decoration: none;
    transition: background .2s, transform .15s, box-shadow .2s;
  }
  .footer-socials a:hover {
    background: var(--brand-pink-light);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(204,0,204,.35);
  }

  /* ── Section Headings ──────────────────── */
  .footer-heading {
    font-family: 'Nunito', 'Segoe UI', sans-serif;
    font-weight: 800;
    font-size: 1rem;
    color: var(--footer-heading);
    margin-bottom: 1.2rem;
    letter-spacing: 0.01em;
  }

  /* ── Links ─────────────────────────────── */
  .footer-links { list-style: none; padding: 0; margin: 0; }
  .footer-links li { margin-bottom: 0.6rem; }
  .footer-links a {
    color: var(--footer-link);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color .2s, padding-left .2s;
    display: inline-block;
  }
  .footer-links a:hover {
    color: var(--footer-link-hover);
    padding-left: 4px;
  }

  /* ── Contact ───────────────────────────── */
  .footer-contact-item {
    color: var(--footer-link);
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
  }
  .footer-contact-item i {
    color: var(--brand-pink);
    font-size: 1rem;
    margin-top: 1px;
    flex-shrink: 0;
  }
  .footer-contact-item a {
    color: var(--footer-link);
    text-decoration: none;
    transition: color .2s;
  }
  .footer-contact-item a:hover { color: var(--brand-pink); }

  /* ── Bottom Bar ────────────────────────── */
  .footer-bottom {
    margin-top: 2.8rem;
    border-top: 1px solid rgba(13,39,80,.12);
    padding: 1.1rem 0;
    text-align: center;
  }
  .footer-bottom p {
    margin: 0;
    font-size: 0.84rem;
    color: #7a8a9a;
  }
  .footer-bottom a {
    color: var(--brand-pink);
    text-decoration: none;
  }
  .footer-bottom a:hover { text-decoration: underline; }
</style>

<footer class="vizafly-footer">
  <div class="container">
    <div class="row gy-4">

      {{-- ── Col 1: Brand ─────────────────── --}}
      <div class="col-lg-3 col-md-6">
        <a href="{{ url('/') }}" class="footer-logo-text d-inline-flex align-items-center">
        VS Global Travels
          <span class="footer-suitcase" aria-hidden="true">
            <span class="fs-handle"></span>
            <span class="fs-body"></span>
            <span class="fs-stripe"></span>
            <span class="fs-wheel l"></span>
            <span class="fs-wheel r"></span>
          </span>
        </a>
        <span class="footer-logo-sub">flight itinerary</span>

        <p class="footer-tagline">
          Trusted since 2021 for reliable, verifiable travel documentation backed by real airline reservations.
        </p>

        <div class="footer-socials">
          <a href="#" aria-label="Facebook">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="#" aria-label="Twitter / X">
            <i class="bi bi-twitter-x"></i>
          </a>
          <a href="#" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
        </div>
      </div>

      {{-- ── Col 2: Quick Links ────────────── --}}
      <div class="col-lg-3 col-md-6 col-sm-6 offset-lg-1">
        <h6 class="footer-heading">Quick Links</h6>
        <ul class="footer-links">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/about') }}">About Us</a></li>
          <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
          <li><a href="{{ url('/contact') }}">Contact Us</a></li>
          <li><a href="{{ url('/faqs') }}">FAQs</a></li>
          <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
        </ul>
      </div>

      {{-- ── Col 3: Services ─────────────── --}}
      <div class="col-lg-2 col-md-6 col-sm-6">
        <h6 class="footer-heading">Services</h6>
        <ul class="footer-links">
          <li><a href="{{ url('/services/flight-itinerary') }}">Flight Itinerary</a></li>
          <li><a href="{{ url('/services/hotel-reservations') }}">Hotel Reservations</a></li>
          <li><a href="{{ url('/services/travel-insurance') }}">Travel Insurance</a></li>
        </ul>
      </div>

      {{-- ── Col 4: Contact Us ────────────── --}}
      <div class="col-lg-3 col-md-6">
        <h6 class="footer-heading">Contact Us</h6>
        <div class="footer-contact-item">
          <i class="bi bi-envelope-fill"></i>
          <span>Email: <a href="mailto:support@vizafly.com">support@vizafly.com</a></span>
        </div>
        <div class="footer-contact-item">
          <i class="bi bi-telephone-fill"></i>
          <span><a href="tel:+10000000000">+1 (000) 000-0000</a></span>
        </div>
        <div class="footer-contact-item">
          <i class="bi bi-clock-fill"></i>
          <span>Mon – Fri &nbsp;9 AM – 6 PM</span>
        </div>
      </div>

    </div>{{-- /row --}}

    {{-- ── Bottom Bar ─────────────────────── --}}
    <div class="footer-bottom">
      <p>
        &copy; {{ date('Y') }} <a href="{{ url('/') }}">vizafly</a>.
        All rights reserved.
      </p>
    </div>

  </div>{{-- /container --}}
</footer>

{{-- Bootstrap Icons (include once in layout if not already) --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/> --}}