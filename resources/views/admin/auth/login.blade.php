{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Parts Plus Innovation Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="admin-body">

    <div class="auth-page">
        <div class="auth-card">

            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div class="auth-title">Parts Plus Innovation Solutions Admin</div>
                <div class="auth-sub">Sign in to your account</div>
            </div>

            @if ($errors->any())
                <div class="flash flash--error" style="margin-bottom:20px;">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            <form action="{{ route('admin.login') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="form-control {{ $errors->has('email') ? 'form-control--error' : '' }}"
                        value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password"
                            class="form-control {{ $errors->has('password') ? 'form-control--error' : '' }}"
                            placeholder="••••••••" required style="padding-right: 40px;">
                        <button type="button"
                            onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#71717A;font-size:13px;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check" style="margin-bottom:24px;">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember" style="color:#A1A1AA;font-size:13px;cursor:pointer;">Keep me signed in</label>
                </div>

                <button type="submit" class="btn btn--primary w-full" style="justify-content:center;padding:11px;">
                    Sign In
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

        </div>
    </div>

</body>

</html>
