<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    :root {
      --overlay: rgba(255, 255, 255, 0.28);
      --overlay-strong: rgba(255, 255, 255, 0.42);
      --bg: #0b0b0b;
    }

    html, body {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      color: #fff;
      background: #000;
    }

    /* Fullscreen hero with background image */
    .hero {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1.05fr 0.95fr;
      gap: 2rem;
      align-items: center;
      padding: 2rem;
      background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1920&auto=format&fit=crop')
        center/cover no-repeat;
    }

    /* Dark gradient overlay for readability */
    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to right, rgba(0,0,0,.45), rgba(0,0,0,.25) 60%, rgba(0,0,0,.25));
    }

    /* Login card styling */
    .login-card {
      position: relative;
      z-index: 1;
      background: var(--overlay);
      border-radius: 14px;
      padding: 28px;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid rgba(255,255,255,.6);
      max-width: 520px;
    }

    @media (max-width: 992px) {
      .hero {
        grid-template-columns: 1fr;
        padding: 1rem;
      }
      .hero .card-area { order: -1; }
    }

    /* Right side hero text */
    .hero-copy {
      color: #fff;
      text-align: right;
      padding-right: 2rem;
    }
    .hero-copy h1 {
      font-size: 48px;
      line-height: 1.05;
      font-weight: 900;
      letter-spacing: .5px;
      margin: 0 0 1rem;
      text-transform: uppercase;
    }

    /* Small screens adjustments */
    @media (max-width: 600px) {
      .hero-copy h1 { font-size: 28px; text-align: left; }
      .login-card { width: 100%; }
      .hero-copy { text-align: left; padding: 0; }
    }
  </style>
</head>
<body>
  <section class="hero" aria-label="Login with background image">
    <div class="card-area d-flex align-items-center justify-content-center">
      <div class="login-card w-100">
        <h2 class="mb-3">Login to Your Account</h2>
        @if(session('error'))
          <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" novalidate>
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label visually-hidden">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required autofocus />
          </div>
          <div class="mb-3">
            <label for="password" class="form-label visually-hidden">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>

    <div class="hero-copy" aria-label="Hero text">
      <div class="wrapper" style="max-width:600px; margin: 0 auto;">
        <h1 style="font-weight:900; margin:0 0 1rem;">THE GOAL OF LIFE IS<br> LIVING IN AGREEMENT<br> WITH NATURE.</h1>
        <div class="d-flex justify-content-end gap-3" aria-label="Social links">
          <a href="#" class="btn btn-light btn-sm" aria-label="Facebook">f</a>
          <a href="#" class="btn btn-light btn-sm" aria-label="Instagram">@</a>
          <a href="#" class="btn btn-light btn-sm" aria-label="LinkedIn">in</a>
          <a href="#" class="btn btn-light btn-sm" aria-label="Twitter">t</a>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>