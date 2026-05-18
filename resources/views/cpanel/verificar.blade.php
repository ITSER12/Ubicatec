<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación | UbicaTec</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('{{ asset("assets/images/logofinal.jpeg") }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Overlay oscuro + difuminado */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,50,0.4);
            backdrop-filter: blur(5px);
            z-index: 1;
        }

        /* Contenedor central */
        .verification-container {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-verification {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            background: rgba(255, 255, 255, 0.95);
        }

        .card-verification h3 {
            color: #28a745; /* verde principal */
            font-weight: 700;
        }

        .code-input {
            font-size: 1.8rem;
            text-align: center;
            letter-spacing: 10px;
            border-radius: 10px;
            padding: 0.5rem;
            border: 1px solid #ccc;
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745, #20c997);
            border: none;
            font-weight: 600;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #20c997, #28a745);
        }

        @media (max-width: 576px) {
            .card-verification {
                padding: 2rem;
                margin: 1rem;
            }
        }
    </style>
</head>

<body>

<div class="verification-container">
    <div class="card-verification">
        <h3 class="text-center mb-3">Verificación</h3>
        <p class="text-center text-muted mb-4">Ingresa el código enviado a tu correo</p>

        <form method="POST" action="{{ route('verificar.codigo') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="codigo" maxlength="6" class="form-control code-input" placeholder="______" required>
            </div>

            <button class="btn btn-success w-100 mb-3">Verificar</button>

            @if(session('error'))
                <div class="alert alert-danger text-center p-2">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>
</div>

</body>
</html>