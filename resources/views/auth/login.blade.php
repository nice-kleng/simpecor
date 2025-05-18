<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Construction Pro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6B35;
            --secondary: #2E4057;
            --light: #F7F7F7;
            --dark: #333333;
        }

        body {
            background-color: var(--light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        .login-image {
            background-image: url('https://images.unsplash.com/photo-1579847188804-ecba0e2ea330?q=80&w=1948&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
            min-height: 500px;
            position: relative;
        }

        .login-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.1) 100%);
        }

        .login-image-content {
            position: relative;
            z-index: 1;
        }

        .login-form {
            padding: 40px;
        }

        .brand {
            margin-bottom: 30px;
        }

        .brand-icon {
            color: var(--primary);
            font-size: 24px;
            margin-right: 10px;
        }

        .brand-name {
            font-weight: 700;
            color: var(--secondary);
            font-size: 24px;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #e55a2b;
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .divider-text {
            padding: 0 15px;
            color: #777;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background-color: #ddd;
        }

        .social-login a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #f1f1f1;
            color: #555;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .social-login a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .forgot-password {
            color: var(--secondary);
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            color: var(--primary);
        }

        .register-link {
            font-size: 14px;
            margin-top: 20px;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card login-card">
                        <div class="row g-0">
                            <div class="col-md-6 d-none d-md-block login-image">
                                <div
                                    class="h-100 d-flex flex-column justify-content-end p-4 text-white login-image-content">
                                    <h2 class="fw-bold">Membangun Masa Depan Bersama PT BETON INDOTAMA SURYA JAYA</h2>
                                    <p class="mb-0">Solusi konstruksi modern untuk proyek impian Anda</p>
                                </div>
                            </div>
                            <div class="col-md-6 bg-white">
                                <div class="login-form">
                                    <div class="brand d-flex align-items-center">
                                        <i class="fas fa-hard-hat brand-icon"></i>
                                        <span class="brand-name">SIMPECOR</span>
                                    </div>

                                    <h4 class="mb-4 fw-bold">Selamat Datang Kembali</h4>
                                    <p class="text-muted mb-4">Silakan masuk untuk mengakses dashboard proyek konstruksi
                                        Anda</p>

                                    <form action="{{ route('auth') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0">
                                                    <i class="fas fa-envelope text-muted"></i>
                                                </span>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    placeholder="nama@perusahaan.com">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <label for="password" class="form-label">Kata Sandi</label>
                                                {{-- <a href="#" class="forgot-password">Lupa kata sandi?</a> --}}
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0">
                                                    <i class="fas fa-lock text-muted"></i>
                                                </span>
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Masukkan kata sandi">
                                            </div>
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="showPassword">
                                            <label class="form-check-label" for="showPassword">Lihat Password</label>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                                    </form>

                                    <div class="divider">
                                        <div class="divider-line"></div>
                                        <span class="divider-text">atau masuk dengan</span>
                                        <div class="divider-line"></div>
                                    </div>

                                    <div class="social-login text-center mb-4">
                                        <a href="#"><i class="fab fa-google"></i></a>
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    </div>

                                    <div class="register-link text-center">
                                        Belum punya akun? <a href="#" id="click-admin">Daftar sekarang</a>
                                    </div>

                                    <div id="alert-container" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const showPassword = document.getElementById('showPassword');
        const password = document.getElementById('password');

        showPassword.addEventListener('change', function() {
            if (this.checked) {
                password.type = 'text';
            } else {
                password.type = 'password';
            }
        });

        document.getElementById('click-admin').addEventListener('click', function(e) {
            e.preventDefault();
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Info!</strong> Silahkan hubungi admin.
            </div>
        `;
            // Hilangkan alert setelah 3 detik
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('hide');
                    setTimeout(() => alertContainer.innerHTML = '', 300); // Hapus dari DOM
                }
            }, 3000);
        });
    </script>
</body>

</html>
