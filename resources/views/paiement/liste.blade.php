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
                                    <h2>Tableau de données paiements</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
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
                        <form method="GET" action="{{ url('/paiement') }}">
                            @include('search')
                        </form>
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" id="select-all" class="i-checks"> <i></i>
                                    </label>
                                </th>

                                <th>Nom</th>
                                <th>Montant</th>
                                <th>Reste</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody id="initial-table-body">
                            <p id="loading-message" style="display: none; text-align: center;">Recherche en cours...</p>

                            @foreach ($paiement as $item)
                                <tr>
                                    <th>
                                        <label>
                                            <input type="checkbox" class="i-checks"> <i></i>
                                        </label>
                                    </th>
                                    <td>{{ $item->nom }}</td>
                                    <td>{{ $item->montant }}</td>
                                    <td>{{ $item->reste }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="notika-icon notika-menu"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/paiement/delete/{{ $item->id }}">Supprimer</a></li>
                                                <li><a class="dropdown-item" href="/paiement/modifier/{{ $item->id }}">Modifier</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tbody id="search-table-body" style="display: none;"></tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const loadingMessage = document.getElementById('loading-message');
        loadingMessage.style.display = 'block';
        let query = this.value;

        // Vérifiez que la saisie n'est pas vide avant de faire la requête
        if (query.length > 2 || query.length === 0) {
            fetch(`/paiement/search-ajax?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    loadingMessage.style.display = 'none';
                    const initialTableBody = document.getElementById('initial-table-body');
                    const searchTableBody = document.getElementById('search-table-body');

                    if (query.length > 2) {
                        initialTableBody.style.display = 'none'; // Masquer les données initiales
                        searchTableBody.style.display = ''; // Afficher les résultats
                    } else {
                        initialTableBody.style.display = ''; // Réafficher les données initiales
                        searchTableBody.style.display = 'none'; // Masquer les résultats
                    }

                    // Vider le tbody de recherche avant de le remplir
                    searchTableBody.innerHTML = '';

                    if (data.items.length > 0) {
                        data.items.forEach(item => {
                            const row = `
                            <tr>
                                <th>
                                    <label>
                                        <input type="checkbox" class="i-checks"> <i></i>
                                    </label>
                                </th>
                                <td>${item.dette.nom}</td>
                                <td>${item.montant}</td>
                                <td>${item.dette.reste}</td>
                                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="notika-icon notika-menu"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="/paiement/delete/${item.id}">Supprimer</a></li>
                                            <li><a class="dropdown-item" href="/paiement/modifier/${item.id}">Modifier</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
                            searchTableBody.innerHTML += row;
                        });
                    } else {
                        searchTableBody.innerHTML = `<tr><td colspan="6" class="text-center">Aucun résultat trouvé</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    loadingMessage.style.display = 'none'; // Toujours cacher en cas d'erreur
                });
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('#data-table-basic thead input[type="checkbox"]').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('#data-table-basic tbody input[type="checkbox"]').prop('checked', isChecked);
        });
    });
</script>
@notifyJs
