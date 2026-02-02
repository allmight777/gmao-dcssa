@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Demandes supprimées (Corbeille)</h6>
                        <p class="text-sm text-muted mb-0">Demandes supprimées que vous pouvez restaurer</p>
                    </div>
                    <a href="{{ route('user.demandes.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Retour aux demandes
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($demandes->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        N° Demande
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Équipement
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Date création
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Date suppression
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <i class="fas fa-trash text-danger me-3"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $demande->Numero_Demande }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $demande->Type_Intervention }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $demande->equipement->numero_inventaire ?? 'N/A' }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $demande->equipement->marque ?? '' }} {{ $demande->equipement->modele ?? '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ $demande->Date_Demande->format('d/m/Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $demande->Heure_Demande }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ $demande->deleted_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $demande->deleted_at->format('H:i') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <form action="{{ route('user.demandes.restore', $demande->ID_Demande) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        onclick="return confirm('Restaurer cette demande ?')"
                                                        title="Restaurer">
                                                    <i class="fas fa-trash-restore"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('user.demandes.forceDelete', $demande->ID_Demande) }}"
                                                  method="POST"
                                                  class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Supprimer définitivement cette demande ? Cette action est irréversible.')"
                                                        title="Supprimer définitivement">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $demandes->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Corbeille vide</h5>
                        <p class="text-muted">Aucune demande supprimée</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    border-radius: 8px;
}

.alert {
    border-radius: 10px;
}
</style>
@endsection
