<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Relatório - GACPAC-TI' }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        header {
            border-bottom: 2px solid #002776; /* fab-blue */
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        header h1 {
            color: #002776;
            font-size: 16pt;
            margin: 0;
            text-transform: uppercase;
        }
        header p {
            margin: 2px 0;
            font-size: 9pt;
            color: #666;
        }
        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            font-size: 8pt;
            color: #888;
            text-align: center;
        }
        .page-number:before {
            content: counter(page);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #111;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        .badge-gray { background: #eee; color: #555; }
        .badge-blue { background: #e0f2fe; color: #0369a1; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header>
        <h1>{{ $title ?? 'GACPAC-TI Relatório' }}</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }} por {{ auth()->user()->name ?? 'Sistema' }}</p>
        <p>{{ config('app.name') }} - Gestão de Ativos de TI</p>
    </header>

    <footer>
        <p>Página <span class="page-number"></span> - Documento gerado eletronicamente.</p>
    </footer>

    <main>
        @yield('content')
    </main>
</body>
</html>
