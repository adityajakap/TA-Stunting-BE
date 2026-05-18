<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f1f5f9;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container-full {
            display: flex;
            min-height: 100vh;
        }

        .left {
            flex: 1;
            background: url('/images/logo.png') center center no-repeat;
            background-size: 70%;
            background-color: #e2e8f0;
        }

        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .form-wrapper h3 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-wrapper .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #2563eb;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 768px) {
            .container-full {
                flex-direction: column;
            }

            .left {
                height: 180px;
                background-size: contain;
            }
        }
    </style>
</head>
<body>

<div class="container-full">

    <div class="left"></div>

    <div class="right">
        <div class="form-wrapper">
            <h3>Masuk Akun</h3>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="nama_anak" class="form-label">Nama Anak</label>
                    <input type="text" name="nama_anak" id="nama_anak" class="form-control" value="{{ old('nama_anak') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div class="text-center mt-3">
                    <small>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></small>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
