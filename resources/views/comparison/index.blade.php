<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DataBolivia | Comparador LATAM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg: #f3f6fb;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --border: #e5e7eb;
            --success: #16a34a;
            --danger: #dc2626;
            --dark: #0f172a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .container {
            width: min(1240px, 92%);
            margin: 0 auto;
        }

        .navbar {
            background: var(--dark);
            color: white;
            padding: 18px 0;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .brand {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.6px;
        }

        .brand span {
            color: #60a5fa;
        }

        .nav-links {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: white;
        }

        .hero {
            padding: 34px 0 22px;
        }

        .hero-card {
            background:
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.45), transparent 30%),
                linear-gradient(135deg, #172554, #1d4ed8);
            color: white;
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 22px 50px rgba(37, 99, 235, 0.25);
        }

        .hero-card h1 {
            margin: 0 0 12px;
            font-size: clamp(30px, 5vw, 48px);
            letter-spacing: -1.5px;
        }

        .hero-card p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.65;
            max-width: 820px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .pill {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: white;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
        }

        .section-title {
            margin: 34px 0 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
        }

        .section-title h2 {
            margin: 0;
            font-size: 24px;
            letter-spacing: -0.5px;
        }

        .section-title p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 0.9fr 1.6fr;
            gap: 18px;
            align-items: start;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.045);
        }

        .filter-form {
            display: grid;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        label {
            font-size: 13px;
            color: var(--muted);
            font-weight: 800;
        }

        select {
            border: 1px solid var(--border);
            background: white;
            border-radius: 13px;
            padding: 11px 12px;
            font-size: 14px;
            color: var(--text);
            outline: none;
            width: 100%;
        }

        select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .countries-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 9px;
        }

        .check-card {
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            background: #f8fafc;
            font-size: 14px;
        }

        .check-card:hover {
            border-color: var(--primary);
        }

        .check-card input {
            width: 16px;
            height: 16px;
        }

        .year-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn {
            border: none;
            background: var(--primary);
            color: white;
            padding: 12px 18px;
            border-radius: 13px;
            font-weight: 900;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .chart-title {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .chart-title h3 {
            margin: 0;
            font-size: 20px;
        }

        .chart-title p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .chart-wrapper {
            position: relative;
            height: 430px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 12px;
            font-weight: 800;
        }

        .badge.positive {
            background: #dcfce7;
            color: #166534;
        }

        .badge.negative {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge.neutral {
            background: #f1f5f9;
            color: #475569;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 18px;
        }

        .summary-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 17px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
        }

        .summary-label {
            color: var(--muted);
            font-size: 13px;
        }

        .summary-value {
            margin-top: 8px;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.6px;
        }

        .summary-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
            align-items: center;
        }

        .table-panel {
            margin-top: 18px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 760px;
        }

        .data-table th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            padding: 13px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            white-space: nowrap;
        }

        .data-table td {
            padding: 13px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .data-table tr:hover {
            background: #f8fafc;
        }

        .rank-number {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
            font-weight: 900;
        }

        .note {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
            margin-top: 12px;
        }

        .empty-state {
            padding: 30px;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            background: #f8fafc;
            color: var(--muted);
            text-align: center;
        }

        .footer {
            padding: 32px 0 44px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 680px) {
            .navbar-content {
                align-items: flex-start;
                flex-direction: column;
            }

            .countries-grid {
                grid-template-columns: 1fr;
            }

            .year-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .hero-card {
                padding: 24px;
            }

            .chart-wrapper {
                height: 320px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="container navbar-content">
            <div class="brand">Data<span>Bolivia</span></div>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Dashboard Bolivia</a>
                <a href="{{ route('comparison') }}">Comparador LATAM</a>
                <a href="/debug/values">Valores</a>
                <a href="/debug/bolivia-summary">Resumen Bolivia</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-card">
                    <h1>Comparador socioeconómico LATAM</h1>

                    <p>
                        Compara indicadores socioeconómicos entre países de Latinoamérica usando datos
                        sincronizados desde la World Bank API hacia la base de datos local de DataBolivia.
                    </p>

                    <div class="hero-meta">
                        <span class="pill">Indicador: {{ $selectedIndicator?->name ?? 'No seleccionado' }}</span>
                        <span class="pill">Periodo: {{ $startYear }} - {{ $endYear }}</span>
                        <span class="pill">Países seleccionados: {{ $selectedCountries->count() }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Configurar comparación</h2>
                    <p>Selecciona hasta 6 países para mantener el gráfico legible.</p>
                </div>
            </div>

            <div class="grid">
                <aside class="panel">
                    <form method="GET" action="{{ route('comparison') }}" class="filter-form">
                        <div class="form-group">
                            <label for="indicator">Indicador</label>
                            <select name="indicator" id="indicator">
                                @foreach ($indicators as $indicator)
                                    <option value="{{ $indicator->code }}" @selected($selectedIndicator && $selectedIndicator->code === $indicator->code)>
                                        {{ $indicator->name }} — {{ $indicator->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Países</label>

                            <div class="countries-grid">
                                @foreach ($countries as $country)
                                    <label class="check-card">
                                        <input type="checkbox" name="countries[]" value="{{ $country->code }}"
                                            @checked(in_array($country->code, $selectedCountryCodes))>
                                        {{ $country->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="year-grid">
                            <div class="form-group">
                                <label for="start_year">Año inicial</label>
                                <select name="start_year" id="start_year">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" @selected($year == $startYear)>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="end_year">Año final</label>
                                <select name="end_year" id="end_year">
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}" @selected($year == $endYear)>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn">Comparar países</button>
                    </form>

                    <p class="note">
                        Nota: el ranking ordena por mayor valor del último año disponible dentro del periodo.
                        En indicadores como desempleo o inflación, un valor mayor no necesariamente representa
                        una mejor situación.
                    </p>
                </aside>

                <section class="panel">
                    <div class="chart-title">
                        <div>
                            <h3>{{ $selectedIndicator?->name ?? 'Indicador no seleccionado' }}</h3>
                            <p>
                                {{ $selectedIndicator?->description ?? 'No hay descripción disponible.' }}
                            </p>
                        </div>

                        <span class="badge neutral">{{ $selectedIndicator?->unit ?? 'Sin unidad' }}</span>
                    </div>

                    @if ($selectedCountries->count() > 0 && $datasets->count() > 0)
                        <div class="chart-wrapper">
                            <canvas id="comparisonChart"></canvas>
                        </div>
                    @else
                        <div class="empty-state">
                            No hay datos suficientes para generar la comparación.
                        </div>
                    @endif
                </section>
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Resumen por país</h2>
                    <p>Último valor disponible y variación reciente dentro del periodo seleccionado.</p>
                </div>
            </div>

            <div class="summary-grid">
                @foreach ($countrySummaries as $summary)
                    <article class="summary-card">
                        <div class="summary-label">{{ $summary['country']->name }}</div>

                        <div class="summary-value">
                            {{ $summary['latest_formatted'] }}
                        </div>

                        <div class="summary-footer">
                            <span>Año: {{ $summary['latest']?->year ?? 'Sin dato' }}</span>

                            <span class="badge {{ $summary['variation_class'] }}">
                                {{ $summary['variation_text'] }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container table-panel">
            <div class="section-title">
                <div>
                    <h2>Ranking del último año disponible</h2>
                    <p>Ordenado por mayor valor registrado para el indicador seleccionado.</p>
                </div>
            </div>

            <div class="panel table-scroll">
                @if ($ranking->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Posición</th>
                                <th>País</th>
                                <th>Año</th>
                                <th>Valor</th>
                                <th>Variación reciente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranking as $index => $item)
                                <tr>
                                    <td>
                                        <span class="rank-number">{{ $index + 1 }}</span>
                                    </td>
                                    <td>{{ $item['country']->name }}</td>
                                    <td>{{ $item['latest']?->year ?? 'Sin dato' }}</td>
                                    <td>{{ $item['latest_formatted'] }}</td>
                                    <td>
                                        <span class="badge {{ $item['variation_class'] }}">
                                            {{ $item['variation_text'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        No hay datos suficientes para mostrar ranking.
                    </div>
                @endif
            </div>
        </section>

        <section class="container table-panel">
            <div class="section-title">
                <div>
                    <h2>Tabla comparativa histórica</h2>
                    <p>Valores por año para los países seleccionados.</p>
                </div>
            </div>

            <div class="panel table-scroll">
                @if ($tableRows->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Año</th>
                                @foreach ($selectedCountries as $country)
                                    <th>{{ $country->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableRows as $row)
                                <tr>
                                    <td>{{ $row['year'] }}</td>

                                    @foreach ($row['countries'] as $countryData)
                                        <td>{{ $countryData['formatted_value'] }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        No hay datos históricos para mostrar.
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            DataBolivia
        </div>
    </footer>

    @if ($selectedCountries->count() > 0 && $datasets->count() > 0)
        <script>
            const labels = @json($labels->values());
            const datasets = @json($datasets);

            const ctx = document.getElementById('comparisonChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;

                                    if (value === null) {
                                        return context.dataset.label + ': Sin dato';
                                    }

                                    return context.dataset.label + ': ' +
                                        new Intl.NumberFormat('es-BO').format(value) +
                                        ' {{ $selectedIndicator?->unit }}';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('es-BO').format(value);
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endif

</body>

</html>
