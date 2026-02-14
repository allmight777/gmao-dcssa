@extends('layouts.admin')

@section('title', 'Maintenance préventive - Équipements')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check mr-2"></i>Maintenance préventive
        </h1>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5>{{ $stats['total'] }}</h5>
                    <small>Total équipements</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5>{{ $stats['preventive'] }}</h5>
                    <small>Maintenance préventive</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5>{{ $stats['mixte'] }}</h5>
                    <small>Maintenance mixte</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h5>{{ $stats['en_maintenance'] }}</h5>
                    <small>En maintenance</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des équipements -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                Équipements disponibles
            </h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Code barre</th>
                        <th>Type</th>
                        <th>Maintenance</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipements as $eq)
                    <tr>
                        <td>{{ $eq->nom }}</td>
                        <td>{{ $eq->code_barres }}</td>
                       <td>{{ $eq->typeEquipement->nom ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $eq->type_maintenance == 'preventive' ? 'success' : 'info' }}">
                                {{ $eq->type_maintenance }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $eq->etat == 'bon' ? 'success' : 'warning' }}">
                                {{ $eq->etat }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('technicien.preventive.planifier', $eq->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-plus"></i> Planifier
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
