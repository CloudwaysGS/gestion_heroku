@include('header')
<style>
    .breadcomb-report {
        display: inline-block;
        margin-right: 10px; /* Espace entre les boutons */
    }
</style>
@notifyCss

<x-notify::notify />
<div class="breadcomb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">
                    @if(session('success'))
                        <div style="color: green;">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="breadcomb-wp">
                                <div class="breadcomb-icon">
                                    <i class="notika-icon notika-windows"></i>
                                </div>
                                <div class="breadcomb-ctn">
                                    <h2>Tableau de données fournisseurs</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ route('fournisseur.create') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter un fournisseur</button></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="data-table-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="data-table-list">
                    <div class="breadcomb-list">
                        <form action="{{ route('fournisseur.index') }}" method="GET" class="mb-4">

                            @include('search')

                        </form>
                    </div>

                    <div class="table-responsive">
                        @if($fournisseurs->isEmpty())
                            <p>Aucun fournisseur trouvé.</p>
                        @else
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($fournisseurs as $fournisseur)
                                <tr>
                                    <td>{{ $fournisseur->nom }}</td>
                                    <td>{{ $fournisseur->telephone }}</td>
                                    <td>{{ $fournisseur->adresse }}</td>
                                    <td>
                                        <a href="{{ route('fournisseur.edit', $fournisseur) }}" class="btn btn-warning btn-sm">Modifier</a>
<form action="{{ route('fournisseur.destroy', $fournisseur) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
</form>
                                    
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <div class="d-flex justify-content-center">
                                {{ $fournisseurs->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
