<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SGAITI') }} - Impressão</title>

    <style>
        /* Reset e Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        /* Container Principal */
        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm;
        }

        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        /* Estilos de Impressão */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-container {
                padding: 0;
                max-width: none;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-before: always;
            }
        }

        /* Estilos de Tela (Preview) */
        @media screen {
            body {
                background: #e5e7eb;
                padding: 20px;
            }

            .print-container {
                background: #fff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 4px;
            }

            .no-print {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
            }

            .btn-print {
                display: inline-flex;
                align-items: center;
                padding: 10px 20px;
                background: #3b82f6;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                cursor: pointer;
                margin-right: 10px;
                transition: background 0.2s;
            }

            .btn-print:hover {
                background: #2563eb;
            }

            .btn-back {
                display: inline-flex;
                align-items: center;
                padding: 10px 20px;
                background: #6b7280;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 14px;
                cursor: pointer;
                text-decoration: none;
                transition: background 0.2s;
            }

            .btn-back:hover {
                background: #4b5563;
            }
        }

        /* Classes Utilitárias */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: 'Courier New', monospace; }
        .text-sm { font-size: 10pt; }
        .text-xs { font-size: 9pt; }
        .text-lg { font-size: 14pt; }
        .text-xl { font-size: 16pt; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .mt-8 { margin-top: 32px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .px-4 { padding-left: 16px; padding-right: 16px; }
        .border-b { border-bottom: 2px solid #000; }
        .border-t { border-top: 1px solid #000; }
        .bg-gray { background-color: #f9fafb; }
    </style>

    @livewireStyles
</head>
<body>
    {{ $slot }}

    <script>
        // Auto-print após carregar
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

    @livewireScripts
</body>
</html>
