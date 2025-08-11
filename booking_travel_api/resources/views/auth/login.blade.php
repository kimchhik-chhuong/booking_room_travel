<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>

   Bootstrap 5.3 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

   Optional: Bootstrap Icons for subtle UI polish 
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
  />

  <style>
    :root {
      /* Refine primary color and accents */
      --bs-primary: #7c3aed;
      --bs-primary-rgb: 124, 58, 237;

      /* Optional: nicer focus ring color */
      --focus-ring: rgba(124, 58, 237, 0.35);
    }

    /* Soft gradient background */
    body {
      min-height: 100vh;
      background:
        radial-gradient(1200px 600px at 10% -10%, rgba(124, 58, 237, 0.08), transparent 60%),
        radial-gradient(1000px 500px at 100% 0%, rgba(16, 185, 129, 0.08), transparent 50%),
        linear-gradient(180deg, #f9fafb 0%, #ffffff 100%);
    }

    /* Centered container */
    .auth-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    /* Card with subtle glass look and shadow */
    .auth-card {
      width: 100%;
      max-width: 460px;
      border: 0;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: saturate(160%) blur(8px);
      box-shadow:
        0 10px 25px rgba(16, 24, 40, 0.06),
        0 2px 8px rgba(16, 24, 40, 0.03);
    }

    /* Header styles */
    .auth-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 6px;
    }
    .auth-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 12px;
      background: linear-gradient(135deg, #7c3aed, #10b981);
      color: #fff;
      box-shadow: 0 6px 14px rgba(124, 58, 237, 0.25);
    }
    .auth-title {
      font-weight: 700;
      letter-spacing: -0.02em;
      margin: 0;
    }
    .auth-subtitle {
      color: #6b7280;
      font-size: 0.95rem;
      margin-bottom: 20px;
    }

    /* Floating labels polish */
    .form-floating > label {
      color: #6b7280;
    }
    .form-control,
    .form-control:focus {
      box-shadow: none;
    }
    .form-control:focus {
      border-color: rgba(var(--bs-primary-rgb), 0.5);
      outline: 0;
      box-shadow: 0 0 0 0.25rem var(--focus-ring);
    }

    /* Primary button enhancements */
    .btn-primary {
      --bs-btn-bg: var(--bs-primary);
      --bs-btn-border-color: var(--bs-primary);
      --bs-btn-hover-bg: #6d28d9;
      --bs-btn-hover-border-color: #6d28d9;
      --bs-btn-focus-shadow-rgb: var(--bs-primary-rgb);
      --bs-btn-active-bg: #5b21b6;
      --bs-btn-active-border-color: #5b21b6;
      border-radius: 12px;
      padding: 0.75rem 1rem;
      font-weight: 600;
    }

    /* Alert rounding */
    .alert {
      border-radius: 12px;
    }

    /* Small helper links */
    .helper {
      font-size: 0.9rem;
      color: #6b7280;
    }
  </style>
</head>
<body>
  <div class="auth-wrapper">
    <div class="auth-card p-4 p-sm-5">
      <div class="auth-header">
        <div class="auth-badge">
          <i class="bi bi-shield-lock" aria-hidden="true"></i>
        </div>
        <h2 class="auth-title h3">Login</h2>
      </div>
      <p class="auth-subtitle">Welcome back! Please enter your credentials to continue.</p>

      @if(session('error'))
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
          <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
          <div>{{ session('error') }}</div>
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        <div class="form-floating mb-3">
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="name@example.com"
            required
            autofocus
          />
          <label for="email"><i class="bi bi-envelope me-2" aria-hidden="true"></i>Email address</label>
        </div>

        <div class="form-floating mb-4">
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="••••••••"
            required
          />
          <label for="password"><i class="bi bi-lock me-2" aria-hidden="true"></i>Password</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Login
        </button>

        <div class="text-center mt-3 helper">
          Tip: Make sure your email is correct before submitting.
        </div>
      </form>
    </div>
  </div>
</body>
</html>