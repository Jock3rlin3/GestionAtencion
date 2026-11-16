<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · SISIFO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --canvas: #111827;
            --surface: #1f2937;
            --surface-raised: #2a3342;
            --control: #374151;
            --control-hover: #4b5563;
            --ink: #f3f4f6;
            --muted: #9ca3af;
            --line: #374151;
            color-scheme: dark;
        }
        body { background: var(--canvas); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; }
        .navbar { background: #111827; border-bottom: 1px solid var(--line); }
        .navbar-brand { letter-spacing: .04em; font-weight: 750; }
        .navbar-brand span { color: var(--muted); }
        .nav-link { color: #d1d5db !important; border-radius: .6rem; padding: .55rem .85rem !important; }
        .nav-link:hover, .nav-link.active { background: var(--control); color: #fff !important; }
        .page-shell { max-width: 1180px; }
        .page-heading { margin: 2rem 0 1.4rem; }
        h1 { font-size: clamp(1.7rem, 3vw, 2.2rem); font-weight: 750; letter-spacing: -.04em; }
        .text-secondary { color: var(--muted) !important; }
        .card { color: var(--ink); background: var(--surface); border: 1px solid var(--line); border-radius: 1rem; box-shadow: 0 8px 26px rgba(0, 0, 0, .16); }
        .card-header { color: var(--ink); background: var(--surface-raised); border-bottom: 1px solid var(--line); border-radius: 1rem 1rem 0 0 !important; }
        .card-footer { color: var(--ink); background: var(--surface) !important; border-top: 1px solid var(--line); }
        .btn-primary { --bs-btn-color: #fff; --bs-btn-bg: var(--control); --bs-btn-border-color: var(--control); --bs-btn-hover-color: #fff; --bs-btn-hover-bg: var(--control-hover); --bs-btn-hover-border-color: var(--control-hover); --bs-btn-active-color: #fff; --bs-btn-active-bg: var(--control-hover); --bs-btn-active-border-color: var(--control-hover); --bs-btn-disabled-bg: var(--control); --bs-btn-disabled-border-color: var(--control); --bs-btn-focus-shadow-rgb: 75, 85, 99; }
        .btn-outline-primary, .btn-outline-secondary, .btn-outline-danger { --bs-btn-color: #d1d5db; --bs-btn-border-color: var(--control-hover); --bs-btn-hover-color: #fff; --bs-btn-hover-bg: var(--control); --bs-btn-hover-border-color: var(--control-hover); --bs-btn-active-color: #fff; --bs-btn-active-bg: var(--control-hover); --bs-btn-active-border-color: var(--control-hover); --bs-btn-disabled-color: var(--muted); --bs-btn-disabled-border-color: var(--control); --bs-btn-focus-shadow-rgb: 75, 85, 99; }
        .btn-light { --bs-btn-color: #e5e7eb; --bs-btn-bg: var(--control); --bs-btn-border-color: var(--control); --bs-btn-hover-color: #fff; --bs-btn-hover-bg: var(--control-hover); --bs-btn-hover-border-color: var(--control-hover); --bs-btn-active-color: #fff; --bs-btn-active-bg: var(--control-hover); --bs-btn-active-border-color: var(--control-hover); }
        .form-control, .form-select { color: var(--ink); background-color: var(--surface-raised); border-color: var(--control); border-radius: .65rem; padding: .65rem .8rem; }
        .form-control::placeholder { color: var(--muted); opacity: 1; }
        .form-control:focus, .form-select:focus { color: var(--ink); background-color: var(--surface-raised); border-color: var(--control-hover); box-shadow: 0 0 0 .2rem rgba(75, 85, 99, .3); }
        .form-select option { color: var(--ink); background: var(--surface-raised); }
        .input-group-text { color: #d1d5db; background: var(--control); border-color: var(--control); }
        .table { --bs-table-color: var(--ink); --bs-table-bg: transparent; --bs-table-border-color: var(--line); --bs-table-striped-color: var(--ink); --bs-table-hover-color: var(--ink); --bs-table-hover-bg: var(--surface-raised); margin-bottom: 0; }
        .table thead th { color: var(--muted); font-size: .72rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; white-space: nowrap; }
        .table > :not(caption) > * > * { padding: .9rem 1rem; border-bottom-color: var(--line); vertical-align: middle; }
        .table tbody tr:last-child td { border-bottom: 0; }
        .sort-link { color: inherit; text-decoration: none; }
        .sort-link:hover { color: #fff; }
        .badge-soft, .badge-pendiente, .badge-atendida, .badge-cancelada { color: #e5e7eb; background: var(--control); }
        .alert-success, .alert-danger { color: #e5e7eb; background: var(--surface-raised); border-color: var(--control); }
        .alert-success .btn-close, .alert-danger .btn-close { filter: invert(1) grayscale(1); }
        .pagination { --bs-pagination-color: #d1d5db; --bs-pagination-bg: var(--surface); --bs-pagination-border-color: var(--line); --bs-pagination-hover-color: #fff; --bs-pagination-hover-bg: var(--control); --bs-pagination-hover-border-color: var(--control-hover); --bs-pagination-focus-color: #fff; --bs-pagination-focus-bg: var(--control); --bs-pagination-focus-box-shadow: 0 0 0 .2rem rgba(75, 85, 99, .3); --bs-pagination-active-color: #fff; --bs-pagination-active-bg: var(--control-hover); --bs-pagination-active-border-color: var(--control-hover); --bs-pagination-disabled-color: var(--muted); --bs-pagination-disabled-bg: var(--surface); --bs-pagination-disabled-border-color: var(--line); }
        .empty-state { padding: 3.5rem 1rem; text-align: center; }
        .required-mark { color: #fca5a5; }
        .invalid-feedback, .form-text.text-danger { color: #fca5a5 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container page-shell">
            <a class="navbar-brand" href="{{ route('tratamientos.index') }}">SISIFO <span>atencion</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Abrir navegacion">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <div class="navbar-nav ms-auto gap-md-2">
                    <a class="nav-link {{ request()->routeIs('tratamientos.*') ? 'active' : '' }}" href="{{ route('tratamientos.index') }}">Tratamientos</a>
                    <a class="nav-link {{ request()->routeIs('consultas.*') ? 'active' : '' }}" href="{{ route('consultas.index') }}">Consultas de atencion</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container page-shell pb-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4 mb-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-4 mb-0" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
