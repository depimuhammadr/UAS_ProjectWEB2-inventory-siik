<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Sistem Inventory Barang</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            overflow-x: hidden;
            position: relative;
            padding: 40px 0;
        }

        .bg-glow-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -100px;
            left: -100px;
            z-index: 0;
        }

        .bg-glow-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, rgba(0,0,0,0) 70%);
            bottom: -150px;
            right: -100px;
            z-index: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 550px;
            padding: 15px;
            z-index: 10;
        }

        .auth-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .form-label {
            font-weight: 500;
            color: #cbd5e1;
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        /* Styling select dropdown options */
        .form-select option {
            background: #1e293b;
            color: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            color: #f8fafc;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        }

        .auth-link {
            color: #a5b4fc;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: #c084fc;
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="text-center mb-4">
                <div class="brand-logo">
                    <i class="bi bi-box-seam-fill"></i> StockFlow
                </div>
                <p class="text-secondary small">Daftar Akun Baru Karyawan</p>
            </div>

            @if($errors->any())
                <div class="alert alert-custom mb-3" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="name" id="name" class="form-control border-start-0" placeholder="John Doe" value="{{ old('name') }}" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="branch_id" class="form-label">Cabang Kantor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-building"></i>
                            </span>
                            <select name="branch_id" id="branch_id" class="form-select border-start-0" required style="border-radius: 0 10px 10px 0;">
                                <option value="" disabled selected>Pilih Cabang...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="division_id" class="form-label">Divisi Kerja</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-briefcase"></i>
                            </span>
                            <select name="division_id" id="division_id" class="form-select border-start-0" required style="border-radius: 0 10px 10px 0;">
                                <option value="" disabled selected>Pilih Divisi...</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 border-white border-opacity-10 text-secondary" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0" placeholder="••••••••" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Daftar Sekarang <i class="bi bi-check-circle ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-3">
                <span class="text-secondary small">Sudah memiliki akun? </span>
                <a href="{{ route('login') }}" class="auth-link small">Masuk disini</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
