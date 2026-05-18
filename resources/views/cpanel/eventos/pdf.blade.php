<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h1>Reporte de Eventos - {{ now()->format('d/m/Y') }}</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Fecha</th>
        <th>Descripción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($eventos as $evento)
        <tr>
            <td>{{ $evento->id_evento }}</td>
            <td>{{ $evento->titulo }}</td>
            <td>{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y') }}</td>
            <td>{{ $evento->descripcion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
