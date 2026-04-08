<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $schema->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; font-size: 13px; background: #fff; }

        .header { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 28px 32px; }
        .header h1 { font-size: 24px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 12px; opacity: 0.85; }

        .meta { display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
        .badge { background: rgba(255,255,255,0.2); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; }

        .content { padding: 24px 32px; }

        .section-title { font-size: 15px; font-weight: 700; color: #4f46e5; border-bottom: 2px solid #e0e7ff; padding-bottom: 8px; margin-bottom: 16px; margin-top: 24px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
        .info-item label { font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-item p { font-size: 13px; color: #111827; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f3f4f6; text-align: left; padding: 10px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; }
        td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
        tr:hover td { background: #fafafa; }

        .pill { display: inline-block; border-radius: 20px; padding: 2px 10px; font-size: 10px; font-weight: 700; }
        .pill-blue { background: #e0e7ff; color: #4338ca; }
        .pill-green { background: #d1fae5; color: #065f46; }
        .pill-yellow { background: #fef3c7; color: #92400e; }
        .pill-red { background: #fee2e2; color: #991b1b; }

        .footer { text-align: center; padding: 16px; color: #9ca3af; font-size: 10px; border-top: 1px solid #f3f4f6; margin-top: 32px; }
        .no-data { text-align: center; color: #9ca3af; padding: 24px; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schema->name }}</h1>
        <p>Gegenereerd op {{ now()->format('d M Y \o\m H:i') }} &bull; {{ $schema->user->name }}</p>
        <div class="meta">
            <span class="badge">{{ ucfirst($schema->difficulty) }}</span>
            <span class="badge">{{ ucfirst($schema->category) }}</span>
            @if($schema->goal && $schema->goal !== 'general')
                <span class="badge">{{ ucfirst(str_replace('_', ' ', $schema->goal)) }}</span>
            @endif
            <span class="badge">{{ $schema->schemaExercises->count() }} oefeningen</span>
        </div>
    </div>

    <div class="content">
        {{-- Beschrijving --}}
        @if($schema->description)
        <div class="section-title">Beschrijving</div>
        <p style="color: #374151; line-height: 1.6; margin-bottom: 20px;">{{ $schema->description }}</p>
        @endif

        {{-- Geplande oefeningen --}}
        <div class="section-title">Geplande Oefeningen</div>
        @if($schema->schemaExercises->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Oefening</th>
                        <th>Spiergroep</th>
                        <th>Doel Sets</th>
                        <th>Doel Reps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schema->schemaExercises as $i => $se)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $se->exercise->name ?? '—' }}</strong></td>
                            <td>
                                @if($se->exercise)
                                    <span class="pill pill-blue">{{ ucfirst($se->exercise->muscle_group) }}</span>
                                @endif
                            </td>
                            <td>{{ $se->target_sets ?? '—' }}</td>
                            <td>{{ $se->target_reps ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">Geen oefeningen gepland.</p>
        @endif

        {{-- Workout Geschiedenis --}}
        @if($schema->exerciseLogs->isNotEmpty())
            <div class="section-title">Workout Geschiedenis</div>
            <table>
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Oefening</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Gewicht</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schema->exerciseLogs->sortByDesc('created_at') as $log)
                        <tr>
                            <td>{{ optional($log->logged_at)->format('d/m/Y') ?? $log->created_at->format('d/m/Y') }}</td>
                            <td>{{ $log->exercise->name ?? '—' }}</td>
                            <td>{{ $log->sets }}</td>
                            <td>{{ $log->reps }}</td>
                            <td>{{ $log->weight }} kg</td>
                            <td>{{ number_format($log->sets * $log->reps * $log->weight, 1) }} kg</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="footer">
        Workout Planner &amp; Tracker &mdash; {{ config('app.name') }} &mdash; {{ now()->format('Y') }}
    </div>
</body>
</html>
