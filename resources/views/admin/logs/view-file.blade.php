@extends('layouts.admin')

@section('title', 'Fichier de Logs')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt mr-2"></i>Fichier de Logs (laravel.log)
        </h1>
        <div>
            <a href="{{ route('admin.logs.dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>Retour au dashboard
            </a>
            <button onclick="refreshLogs()" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt mr-1"></i>Rafraîchir
            </button>
            @if(auth()->user()->isAdmin())
            <form action="{{ route('admin.logs.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir vider le fichier de logs ?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash mr-1"></i>Vider le fichier
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Informations sur le fichier -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ round($fileInfo['taille'] / 1024, 2) }} KB</h5>
                            <small>Taille du fichier</small>
                        </div>
                        <i class="fas fa-weight-hanging fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ $fileInfo['lignes'] }}</h5>
                            <small>Nombre de lignes</small>
                        </div>
                        <i class="fas fa-file-lines fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">{{ \Carbon\Carbon::createFromTimestamp($fileInfo['modification'])->format('d/m/Y H:i') }}</h5>
                            <small>Dernière modification</small>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="font-weight-bold mb-0">500</h5>
                            <small>Lignes affichées</small>
                        </div>
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.logs.fichier') }}" class="form-inline">
                <div class="row w-100">
                    <div class="col-md-4 mb-2">
                        <select name="niveau" class="form-control w-100">
                            <option value="">Tous les niveaux</option>
                            <option value="ERROR" {{ request('niveau') == 'ERROR' ? 'selected' : '' }}>ERROR</option>
                            <option value="CRITICAL" {{ request('niveau') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                            <option value="ALERT" {{ request('niveau') == 'ALERT' ? 'selected' : '' }}>ALERT</option>
                            <option value="EMERGENCY" {{ request('niveau') == 'EMERGENCY' ? 'selected' : '' }}>EMERGENCY</option>
                            <option value="WARNING" {{ request('niveau') == 'WARNING' ? 'selected' : '' }}>WARNING</option>
                            <option value="NOTICE" {{ request('niveau') == 'NOTICE' ? 'selected' : '' }}>NOTICE</option>
                            <option value="INFO" {{ request('niveau') == 'INFO' ? 'selected' : '' }}>INFO</option>
                            <option value="DEBUG" {{ request('niveau') == 'DEBUG' ? 'selected' : '' }}>DEBUG</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search mr-1"></i>Filtrer
                        </button>
                    </div>
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('admin.logs.fichier') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-undo mr-1"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenu du fichier log -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-code mr-2"></i>Contenu du fichier (dernières 500 lignes)
            </h6>
            <span class="badge badge-secondary p-2">Total: {{ count($lines) }} lignes</span>
        </div>
        <div class="card-body p-0">
            <div class="log-container" style="max-height: 600px; overflow-y: auto; background-color: #1e1e1e; color: #d4d4d4; font-family: 'Courier New', monospace; font-size: 12px; padding: 15px; border-radius: 5px;">
                @forelse($lines as $line)
                    @php
                        $lineClass = '';
                        if (strpos($line, 'ERROR') !== false) $lineClass = 'log-error';
                        elseif (strpos($line, 'CRITICAL') !== false) $lineClass = 'log-critical';
                        elseif (strpos($line, 'ALERT') !== false) $lineClass = 'log-alert';
                        elseif (strpos($line, 'EMERGENCY') !== false) $lineClass = 'log-emergency';
                        elseif (strpos($line, 'WARNING') !== false) $lineClass = 'log-warning';
                        elseif (strpos($line, 'NOTICE') !== false) $lineClass = 'log-notice';
                        elseif (strpos($line, 'INFO') !== false) $lineClass = 'log-info';
                        elseif (strpos($line, 'DEBUG') !== false) $lineClass = 'log-debug';
                    @endphp
                    <div class="log-line {{ $lineClass }}" style="border-bottom: 1px solid #333; padding: 3px 0; white-space: pre-wrap;">
                        {{ $line }}
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Le fichier de logs est vide</h5>
                        <p class="text-muted">Aucune erreur ou activité enregistrée pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-light">
            <small class="text-muted">
                <i class="fas fa-info-circle mr-1"></i>
                Les dernières 500 lignes sont affichées. Les lignes sont colorées selon leur niveau de gravité.
            </small>
        </div>
    </div>
</div>

<style>
.log-error { color: #ff6b6b; background-color: rgba(255, 107, 107, 0.1); }
.log-critical { color: #ff4757; background-color: rgba(255, 71, 87, 0.15); font-weight: bold; }
.log-alert { color: #ffa502; background-color: rgba(255, 165, 2, 0.1); }
.log-emergency { color: #ff3838; background-color: rgba(255, 56, 56, 0.2); font-weight: bold; }
.log-warning { color: #ffcc00; background-color: rgba(255, 204, 0, 0.1); }
.log-notice { color: #70a1ff; background-color: rgba(112, 161, 255, 0.05); }
.log-info { color: #1e90ff; background-color: rgba(30, 144, 255, 0.05); }
.log-debug { color: #95a5a6; background-color: rgba(149, 165, 166, 0.05); }

.log-line:hover {
    background-color: #2d2d2d;
}

/* Scrollbar personnalisée */
.log-container::-webkit-scrollbar {
    width: 8px;
}
.log-container::-webkit-scrollbar-track {
    background: #2d2d2d;
}
.log-container::-webkit-scrollbar-thumb {
    background: #666;
    border-radius: 4px;
}
.log-container::-webkit-scrollbar-thumb:hover {
    background: #888;
}
</style>

@push('scripts')
<script>
function refreshLogs() {
    location.reload();
}

// Ajouter la recherche en temps réel (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Rechercher dans les logs...';
    searchInput.className = 'form-control form-control-sm mb-3';
    searchInput.style.width = '300px';

    const filterSection = document.querySelector('.card-header.py-3.d-flex');
    filterSection.appendChild(searchInput);

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const logLines = document.querySelectorAll('.log-line');

        logLines.forEach(line => {
            const text = line.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                line.style.display = 'block';
                if (searchTerm) {
                    line.innerHTML = line.textContent.replace(
                        new RegExp(searchTerm, 'gi'),
                        match => `<span style="background-color: #ff6b6b; color: #fff;">${match}</span>`
                    );
                } else {
                    line.innerHTML = line.textContent;
                }
            } else {
                line.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
