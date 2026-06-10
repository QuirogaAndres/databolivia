<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DataBolivia | Dashboard Bolivia</title>
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
            --warning: #d97706;
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

        .hero-grid {
            display: grid;
            grid-template-columns: 1.6fr 0.8fr;
            gap: 24px;
            align-items: center;
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: clamp(30px, 5vw, 48px);
            letter-spacing: -1.5px;
        }

        .hero p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.65;
            max-width: 760px;
        }

        .hero-box {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            padding: 18px;
        }

        .hero-box-label {
            color: #bfdbfe;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .hero-box-value {
            font-size: 28px;
            font-weight: 900;
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

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.045);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        }

        .card-label {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.4;
            min-height: 38px;
        }

        .card-value {
            margin-top: 12px;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.8px;
        }

        .card-footer {
            margin-top: 12px;
            color: var(--muted);
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: 0.9fr 1.5fr;
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
            gap: 14px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 18px;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.4px;
        }

        .stat-note {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .selected-indicator-title {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .selected-indicator-title h3 {
            margin: 0;
            font-size: 20px;
        }

        .selected-indicator-title p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .chart-wrapper {
            position: relative;
            height: 390px;
        }

        .table-panel {
            margin-top: 18px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            overflow: hidden;
        }

        .data-table th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            padding: 13px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        .data-table td {
            padding: 13px;
            border-bottom: 1px solid var(--border);
        }

        .data-table tr:hover {
            background: #f8fafc;
        }

        .value-strong {
            font-weight: 800;
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
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .hero-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .navbar-content {
                align-items: flex-start;
                flex-direction: column;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .year-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .hero-card {
                padding: 24px;
            }

            .chart-wrapper {
                height: 310px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="container navbar-content">
            <div class="brand">Data<span>Bolivia</span></div>

            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="/debug/database">Debug BD</a>
                <a href="/debug/values">Valores</a>
                <a href="/debug/bolivia-summary">Resumen Bolivia</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-card">
                    <div class="hero-grid">
                        <div>
                            <h1>Dashboard socioeconómico de {{ $country->name }}</h1>

                            <p>
                                Plataforma inicial de análisis de indicadores socioeconómicos obtenidos desde
                                la World Bank API y almacenados localmente en MySQL para visualización,
                                comparación y futuros reportes.
                            </p>

                            <div class="hero-meta">
                                <span class="pill">País: {{ $country->name }}</span>
                                <span class="pill">Región: {{ $country->region }}</span>
                                <span class="pill">Ingreso: {{ $country->income_level }}</span>
                                <span class="pill">Periodo disponible: {{ $minYear }} -
                                    {{ $maxYear }}</span>
                            </div>
                        </div>

                        <div class="hero-box">
                            <div class="hero-box-label">Indicadores destacados</div>
                            <div class="hero-box-value">{{ $featuredIndicators->count() }}</div>
                            <div class="hero-box-label" style="margin-top: 12px;">
                                Última actualización visual:
                                {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Indicadores destacados</h2>
                    <p>Últimos valores disponibles de Bolivia según los datos sincronizados.</p>
                </div>
            </div>

            <div class="cards-grid">
                @foreach ($cards as $card)
                    @php
                        $latest = $card['latest_value'];
                        $variation = $card['variation'];
                        $variationText =
                            $variation === null
                                ? 'Sin comparación'
                                : ($variation >= 0 ? '+' : '') . number_format($variation, 2, ',', '.') . '%';
                    @endphp

                    <article class="card">
                        <div class="card-label">
                            {{ $card['indicator']->name }}
                        </div>

                        <div class="card-value">
                            {{ $card['formatted_value'] }}
                        </div>

                        <div class="card-footer">
                            <span>Año: {{ $latest?->year ?? 'Sin dato' }}</span>

                            <span class="badge {{ $card['trend_class'] }}">
                                {{ $card['trend_label'] }} {{ $variation !== null ? $variationText : '' }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Análisis histórico</h2>
                    <p>Filtra un indicador y un rango de años para ver su evolución.</p>
                </div>
            </div>

            <div class="dashboard-grid">
                <aside class="panel">
                    <form method="GET" action="{{ route('dashboard') }}" class="filter-form">
                        <div class="form-group">
                            <label for="indicator">Indicador</label>
                            <select name="indicator" id="indicator">
                                @foreach ($featuredIndicators as $indicator)
                                    <option value="{{ $indicator->code }}" @selected($selectedIndicator && $selectedIndicator->code === $indicator->code)>
                                        {{ $indicator->name }} — {{ $indicator->code }}
                                    </option>
                                @endforeach
                            </select>
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

                        <button type="submit" class="btn">Actualizar análisis</button>
                    </form>

                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-label">Último valor del periodo</div>
                            <div class="stat-value">{{ $stats['latest_formatted'] }}</div>
                            <div class="stat-note">
                                Año: {{ $stats['latest']?->year ?? 'Sin dato' }}
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-label">Variación reciente</div>
                            <div class="stat-value">
                                <span class="badge {{ $stats['variation_class'] }}">
                                    {{ $stats['variation_text'] }}
                                </span>
                            </div>
                            <div class="stat-note">
                                Comparado con:
                                {{ $stats['previous']?->year ?? 'Sin dato anterior' }}
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-label">Promedio del periodo</div>
                            <div class="stat-value">{{ $stats['average_formatted'] }}</div>
                            <div class="stat-note">
                                Registros: {{ $stats['records_count'] }}
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-label">Valor máximo</div>
                            <div class="stat-value">{{ $stats['max_formatted'] }}</div>
                            <div class="stat-note">
                                Año: {{ $stats['max']?->year ?? 'Sin dato' }}
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-label">Valor mínimo</div>
                            <div class="stat-value">{{ $stats['min_formatted'] }}</div>
                            <div class="stat-note">
                                Año: {{ $stats['min']?->year ?? 'Sin dato' }}
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-label">Unidad</div>
                            <div class="stat-value">{{ $selectedIndicator?->unit ?? 'Sin unidad' }}</div>
                            <div class="stat-note">
                                Código: {{ $selectedIndicator?->code ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </aside>

                <section class="panel">
                    <div class="selected-indicator-title">
                        <div>
                            <h3>{{ $selectedIndicator?->name ?? 'Indicador no seleccionado' }}</h3>
                            <p>
                                {{ $selectedIndicator?->description ?? 'No hay descripción disponible.' }}
                            </p>
                        </div>

                        <span class="badge neutral">
                            {{ $startYear }} - {{ $endYear }}
                        </span>
                    </div>

                    @if ($selectedIndicator && $chartValues->count() > 0)
                        <div class="chart-wrapper">
                            <canvas id="indicatorChart"></canvas>
                        </div>
                    @else
                        <div class="empty-state">
                            No hay datos sincronizados para este indicador en el rango seleccionado.
                        </div>
                    @endif
                </section>
            </div>
        </section>

        <section class="container table-panel">
            <div class="section-title">
                <div>
                    <h2>Tabla histórica</h2>
                    <p>
                        Serie de datos del indicador seleccionado para el periodo
                        {{ $startYear }} - {{ $endYear }}.
                    </p>
                </div>
            </div>

            <div class="panel">
                @if ($chartValues->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Valor</th>
                                <th>Unidad</th>
                                <th>Indicador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chartValues->sortByDesc('year') as $value)
                                <tr>
                                    <td>{{ $value->year }}</td>
                                    <td class="value-strong">
                                        {{ number_format((float) $value->value, 6, ',', '.') }}
                                    </td>
                                    <td>{{ $selectedIndicator->unit }}</td>
                                    <td>{{ $selectedIndicator->code }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        Todavía no existen valores para mostrar en la tabla.
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            DataBolivia — Laravel, MySQL, Chart.js y World Bank API.
        </div>
    </footer>

    @if ($selectedIndicator && $chartValues->count() > 0)
        <script>
            const labels = @json($chartLabels);
            const dataValues = @json($chartData);

            const ctx = document.getElementById('indicatorChart');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ $selectedIndicator->name }}',
                        data: dataValues,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        borderWidth: 3,
                        fill: false
                    }]
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
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;

                                    return '{{ $selectedIndicator->name }}: ' +
                                        new Intl.NumberFormat('es-BO').format(value) +
                                        ' {{ $selectedIndicator->unit }}';
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
