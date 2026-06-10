<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DataBolivia | Dashboard Bolivia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Chart.js para el primer gráfico --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #162033;
            --muted: #667085;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --border: #e5e7eb;
            --success: #16a34a;
            --danger: #dc2626;
            --warning: #d97706;
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
            width: min(1200px, 92%);
            margin: 0 auto;
        }

        .navbar {
            background: #0f172a;
            color: white;
            padding: 18px 0;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand span {
            color: #60a5fa;
        }

        .nav-links {
            display: flex;
            gap: 18px;
            font-size: 14px;
        }

        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
        }

        .nav-links a:hover {
            color: white;
        }

        .hero {
            padding: 38px 0 26px;
        }

        .hero-card {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            border-radius: 24px;
            padding: 34px;
            color: white;
            box-shadow: 0 20px 45px rgba(37, 99, 235, 0.25);
        }

        .hero-card h1 {
            margin: 0 0 12px;
            font-size: clamp(30px, 5vw, 48px);
            letter-spacing: -1.5px;
        }

        .hero-card p {
            max-width: 760px;
            margin: 0;
            color: #dbeafe;
            line-height: 1.6;
            font-size: 16px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
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
            align-items: end;
            gap: 14px;
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
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
        }

        .card-label {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.4;
            min-height: 36px;
        }

        .card-value {
            margin-top: 12px;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .card-footer {
            margin-top: 12px;
            color: var(--muted);
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .variation {
            font-weight: 700;
        }

        .variation.positive {
            color: var(--success);
        }

        .variation.negative {
            color: var(--danger);
        }

        .variation.neutral {
            color: var(--muted);
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            margin-bottom: 24px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 260px;
        }

        label {
            font-size: 13px;
            color: var(--muted);
            font-weight: 700;
        }

        select {
            border: 1px solid var(--border);
            background: white;
            border-radius: 12px;
            padding: 11px 12px;
            font-size: 14px;
            color: var(--text);
            outline: none;
        }

        select:focus {
            border-color: var(--primary);
        }

        .btn {
            border: none;
            background: var(--primary);
            color: white;
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .chart-wrapper {
            position: relative;
            height: 380px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            font-size: 14px;
        }

        .data-table th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            padding: 13px;
            border-bottom: 1px solid var(--border);
        }

        .data-table td {
            padding: 13px;
            border-bottom: 1px solid var(--border);
        }

        .data-table tr:hover {
            background: #f8fafc;
        }

        .empty-state {
            padding: 28px;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
            color: var(--muted);
            text-align: center;
        }

        .footer {
            padding: 28px 0 40px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 1000px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .navbar-content {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 640px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }

            .hero-card {
                padding: 24px;
            }

            .chart-wrapper {
                height: 300px;
            }

            .nav-links {
                flex-wrap: wrap;
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
                    <h1>Dashboard socioeconómico de {{ $country->name }}</h1>

                    <p>
                        Visualización inicial de indicadores socioeconómicos obtenidos desde la World Bank API
                        y almacenados localmente en MySQL para análisis, comparación y futuros reportes.
                    </p>

                    <div class="hero-meta">
                        <span class="pill">País: {{ $country->name }}</span>
                        <span class="pill">Región: {{ $country->region }}</span>
                        <span class="pill">Nivel de ingreso: {{ $country->income_level }}</span>
                        <span class="pill">Actualizado: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Indicadores destacados</h2>
                    <p>Últimos valores disponibles sincronizados desde la base local.</p>
                </div>
            </div>

            <div class="cards-grid">
                @foreach ($cards as $card)
                    @php
                        $latest = $card['latest_value'];
                        $variation = $card['variation'];

                        $variationClass = 'neutral';
                        $variationText = 'Sin variación';

                        if ($variation !== null) {
                            $variationClass = $variation >= 0 ? 'positive' : 'negative';
                            $variationText =
                                ($variation >= 0 ? '+' : '') . number_format($variation, 2, ',', '.') . '%';
                        }
                    @endphp

                    <article class="card">
                        <div class="card-label">
                            {{ $card['indicator']->name }}
                        </div>

                        <div class="card-value">
                            {{ $card['formatted_value'] }}
                        </div>

                        <div class="card-footer">
                            <span>
                                Año:
                                {{ $latest?->year ?? 'Sin dato' }}
                            </span>

                            <span class="variation {{ $variationClass }}">
                                {{ $variationText }}
                            </span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Evolución histórica</h2>
                    <p>Selecciona un indicador para visualizar su comportamiento por año.</p>
                </div>
            </div>

            <div class="panel">
                <form method="GET" action="{{ route('dashboard') }}" class="filter-row">
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

                    <button type="submit" class="btn">Actualizar gráfico</button>
                </form>

                @if ($selectedIndicator && $chartValues->count() > 0)
                    <div class="chart-wrapper">
                        <canvas id="indicatorChart"></canvas>
                    </div>
                @else
                    <div class="empty-state">
                        No hay datos sincronizados para este indicador.
                        Ejecuta el comando de sincronización y vuelve a cargar el dashboard.
                    </div>
                @endif
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <div>
                    <h2>Tabla de datos</h2>
                    <p>
                        Serie histórica de
                        <strong>{{ $selectedIndicator?->name ?? 'indicador no seleccionado' }}</strong>.
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
                                    <td>{{ number_format((float) $value->value, 6, ',', '.') }}</td>
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
            DataBolivia 
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
                        pointHoverRadius: 6,
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
