<nav class="navbar navbar-expand-lg vizafly-nav sticky-top">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center gap-1 logo-wrap" href="#">
      <div>
        <div class="logo-top">
          VS Global Travels
          <span class="suitcase-icon" aria-hidden="true">
            <span class="handle"></span>
            <span class="body"></span>
            <span class="stripe"></span>
            <span class="wheel l"></span>
            <span class="wheel r"></span>
          </span>
        </div>
        <div class="logo-sub">flight itinerary</div>
      </div>
    </a>

    <!-- Mobile toggler -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links -->
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">FAQs</a>
        </li>
        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
          <a href="{{ route('home-page')}}" class="nav-link">Book Now</a>
        </li>
      </ul>
    </div>

  </div>
</nav>

<style>
    /* ── NAVBAR ─────────────────────────────── */
    .vizafly-nav {
      background: #ffffff;
      box-shadow: 0 2px 18px rgba(13,39,80,.09);
      padding: 0.55rem 0;
    }

    /* Logo text */
    .logo-wrap {
      display: flex;
      flex-direction: column;
      line-height: 1;
      text-decoration: none;
    }
    .logo-top {
      font-family: 'Nunito', sans-serif;
      font-weight: 900;
      font-size: 1.65rem;
      letter-spacing: -0.5px;
      color: var(--brand-navy);
    }
    .logo-top .z-letter {
      color: var(--brand-pink);
    }
    .logo-sub {
      font-family: 'DM Sans', sans-serif;
      font-size: 0.63rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      color: var(--brand-pink);
      text-transform: lowercase;
      margin-top: 1px;
      padding-left: 2px;
    }

    /* Suitcase icon (pure CSS) */
    .suitcase-icon {
      display: inline-block;
      position: relative;
      width: 28px;
      height: 34px;
      margin-left: 4px;
      vertical-align: middle;
      top: -3px;
    }
    .suitcase-icon .body {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 28px;
      height: 26px;
      background: var(--brand-pink);
      border-radius: 5px;
    }
    .suitcase-icon .handle {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 12px;
      height: 10px;
      border: 3px solid var(--brand-pink);
      border-bottom: none;
      border-radius: 4px 4px 0 0;
    }
    .suitcase-icon .stripe {
      position: absolute;
      bottom: 8px;
      left: 0;
      width: 100%;
      height: 3px;
      background: rgba(255,255,255,0.35);
      border-radius: 2px;
    }
    .suitcase-icon .wheel {
      position: absolute;
      bottom: 1px;
      width: 5px;
      height: 5px;
      background: #fff;
      border-radius: 50%;
    }
    .suitcase-icon .wheel.l { left: 4px; }
    .suitcase-icon .wheel.r { right: 4px; }

    /* Nav links */
    .vizafly-nav .nav-link {
      font-family: 'DM Sans', sans-serif;
      font-weight: 600;
      font-size: 0.92rem;
      color: #2a2a2a !important;
      padding: 0.45rem 1rem !important;
      border-radius: 6px;
      transition: color .2s, background .2s;
    }
    .vizafly-nav .nav-link:hover {
      color: var(--brand-pink) !important;
      background: rgba(204,0,204,.06);
    }
    .vizafly-nav .nav-link.active {
      color: var(--brand-pink) !important;
    }

    /* Book Now button */
    .btn-book {
      background: var(--brand-pink-btn);
      color: #fff !important;
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: 0.92rem;
      letter-spacing: 0.02em;
      padding: 0.48rem 1.35rem;
      border-radius: 8px;
      border: none;
      transition: background .2s, box-shadow .2s, transform .15s;
      white-space: nowrap;
    }
    .btn-book:hover {
      background: var(--brand-pink-light);
      box-shadow: 0 4px 18px rgba(204,0,204,.35);
      transform: translateY(-1px);
    }
    .btn-book:active {
      transform: translateY(0);
    }

    /* Toggler */
    .navbar-toggler {
      border-color: var(--brand-pink);
    }
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='%23cc00cc' stroke-width='2.5' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    /* ── HERO (demo section) ─────────────────── */
    .hero {
      min-height: calc(100vh - 68px);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      padding: 3rem 1rem;
    }
    .hero h1 {
      font-family: 'Nunito', sans-serif;
      font-weight: 900;
      font-size: clamp(2rem, 5vw, 3.6rem);
      color: var(--brand-navy);
      line-height: 1.15;
    }
    .hero h1 span { color: var(--brand-pink); }
    .hero p {
      color: #5a6a80;
      font-size: 1.05rem;
      max-width: 500px;
      margin: 1rem auto 2rem;
    }
    .hero .btn-book-lg {
      background: var(--brand-pink-btn);
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: 1rem;
      padding: 0.72rem 2.2rem;
      border-radius: 10px;
      border: none;
      box-shadow: 0 6px 24px rgba(204,0,204,.28);
      transition: background .2s, transform .15s, box-shadow .2s;
    }
    .hero .btn-book-lg:hover {
      background: var(--brand-pink-light);
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(204,0,204,.38);
    }

    /* floating badge */
    .badge-tag {
      display: inline-block;
      background: rgba(204,0,204,.1);
      color: var(--brand-pink);
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      padding: 0.3rem 0.85rem;
      border-radius: 50px;
      margin-bottom: 1.2rem;
      border: 1px solid rgba(204,0,204,.2);
    }

</style>