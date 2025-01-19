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
                                    <h2>Tableau de données Sortie dépôt</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ route('depotSortie.create') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter un produit</button></a>
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
                    <form method="GET" action="{{ url('/depotSortie') }}" id="search-form">
                        @include('search')
                    </form>
                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach ($depots as $item)
                                <tr class="data-row">
                                    <td>{{ $item->nomProduit }}</td>
                                    <td>{{ $item->qteSortie }}</td>
                                    <td>{{ number_format($item->prix, 2) }} FCFA</td>
                                    <td>{{ number_format($item->total, 2) }} FCFA</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="notika-icon notika-menu"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('depotSortie.confirmDestroy', $item->id) }} }}">supprimer</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="{{ route('depotSortie.edit', $item->id) }}">modifier</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Controls -->
                        <div id="pagination-controls">
                            <button id="prev" class="btn" disabled>Précédent</button>
                            <span id="page-number">Page 1</span>
                            <button id="next" class="btn">Suivant</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
{{--<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');  // L'input de recherche
        const tableBody = document.getElementById('table-body');      // Le corps du tableau
        const rowsPerPage = 10; // Nombre d'éléments par page
        let currentPage = 1; // Page courante
        let allData = []; // Stocker toutes les données de produits

        // Fonction pour mettre à jour l'affichage du tableau
        function updateTable() {
            tableBody.innerHTML = ''; // Vider le contenu du tableau

            // Calculer les indices de début et de fin pour la page actuelle
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedData = allData.slice(startIndex, endIndex); // Extraire les données pour la page actuelle

            if (paginatedData.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4">Aucun résultat trouvé.</td></tr>';
                return;
            }

            // Ajouter les lignes du tableau
            paginatedData.forEach(item => {
                const row = `
                <tr>
                    <td>${item.nom}</td>
                    <td>${item.qteProduit}</td>
                    <td>${item.prixProduit}</td>
                    <td>${item.montant}</td>
                    <td>${item.prixAchat}</td>
                     <td>${new Date(item.created_at).toLocaleDateString()}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="notika-icon notika-menu"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="/depot/supp/${item.id}">Supprimer</a></li>
                                    <li><a class="dropdown-item" href="/depot/${item.id}/edit">Modifier</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            // Mettre à jour le numéro de la page
            document.getElementById('page-number').textContent = `Page ${currentPage}`;
            document.getElementById('prev').disabled = currentPage === 1;
            document.getElementById('next').disabled = currentPage === Math.ceil(allData.length / rowsPerPage);
        }

        // Fonction pour récupérer les produits depuis le backend avec recherche
        function fetchProducts(query = '') {
            fetch(`/depot/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    allData = data; // Stocker les produits reçus
                    updateTable();   // Mettre à jour l'affichage du tableau
                })
                .catch(error => console.error('Erreur:', error));
        }

        // Gérer la saisie de l'utilisateur dans le champ de recherche
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value;

            if (query.length > 2) {
                fetchProducts(query); // Rechercher des produits si plus de 2 caractères
            } else {
                fetchProducts(); // Recharger tous les produits si la recherche est vide
            }
        });

        // Fonction pour changer de page
        function changePage(direction) {
            const totalPages = Math.ceil(allData.length / rowsPerPage);

            if (direction === -1 && currentPage > 1) {
                currentPage--; // Page précédente
            } else if (direction === 1 && currentPage < totalPages) {
                currentPage++; // Page suivante
            }

            updateTable(); // Mettre à jour le tableau
        }

        // Ajouter des écouteurs d'événements pour les boutons de pagination
        document.getElementById('prev').addEventListener('click', function() {
            changePage(-1);  // Aller à la page précédente
        });

        document.getElementById('next').addEventListener('click', function() {
            changePage(1);  // Aller à la page suivante
        });

        // Initialiser le tableau avec tous les produits
        fetchProducts();  // Charger les produits au démarrage
    });
</script>--}}

<script>
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('dropdown-item') && e.target.textContent.trim() === 'Supprimer') {
            e.preventDefault();
            const url = e.target.getAttribute('href');
            const detteName = e.target.closest('tr').querySelector('td:first-child').textContent.trim();

            if (confirm(`Êtes-vous sûr de vouloir supprimer "${detteName}" ?`)) {
                window.location.href = url;
            }
        }
    });

</script>


@notifyJs
