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
                                    <h2>Tableau de données entrées</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ url('/entree/ajout') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter une entrée</button></a>
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

                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="select-all" class="i-checks"> <i></i>
                                    </label>
                                </th>

                                <th>Libellé</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            @foreach ($entree as $entree)
                                <tbody>
                                <tr>
                                    <th>
                                        <label>
                                            <input type="checkbox" id="select-all" class="i-checks"> <i></i>
                                        </label>
                                    </th>

                                    <td>{{ $entree->nomProduit }}</td>
                                    <td>{{ $entree->qteEntree }}</td>
                                    <td>{{ $entree->prix }}</td>
                                    <td>
                                        <!-- Bouton Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-success success-icon-notika dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="notika-icon notika-menu"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="{{url('/entree/delete', $entree->id)}}">supprimer</a></li>
                                                <li><a class="dropdown-item" href="{{ route('entree.modifier', $entree->id) }}">modifier</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
<script>
    $(document).ready(function () {
        $('#data-table-basic thead input[type="checkbox"]').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#data-table-basic tbody input[type="checkbox"]').prop('checked', isChecked);
        });
    });
</script>
@notifyJs
