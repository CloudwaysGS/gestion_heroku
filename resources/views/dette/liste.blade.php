@include('header')
<style>
    .breadcomb-report {
        display: inline-block;
        margin-right: 10px; /* Espace entre les boutons */
    }

    #pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    #pagination-controls button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 15px;
        margin: 0 5px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #pagination-controls button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    #pagination-controls button:hover:not(:disabled) {
        background-color: #0056b3;
    }

    #pagination-controls #page-number {
        margin: 0 10px;
        font-weight: bold;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .spinner {
        width: 50px;
        height: 50px;
        margin: 0 auto;
        border: 6px solid #f3f3f3; /* Couleur du cercle */
        border-top: 6px solid #007bff; /* Couleur de l'animation */
        border-radius: 50%;
        animation: spin 1s linear infinite;
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
                                    <h2>Tableau de données dettes</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ url('/dette/ajout') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter une dette</button></a>
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
                    <!-- Formulaire de recherche -->
                    <form method="GET" action="{{ url('/dette') }}">
                        @include('search')
                    </form>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Montant</th>
                                <th>Reste</th>
                                <th>Dépot</th>
                                <th>Date</th>
                                <th>Etat</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">

                            <div id="loader" style="display: none; text-align: center; margin: 20px;">
                                <div class="spinner"></div>
                                <p>Chargement...</p>
                            </div>

                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div id="pagination-controls" class="d-flex justify-content-between">
                            <button style="background-color: #00c292; color: white;" id="prev" disabled>Précédent</button>
                            <span id="page-number">Page 1</span>
                            <button style="background-color: #00c292; color: white;" id="next" disabled>Suivant</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('table-body');
        const prevButton = document.getElementById('prev');
        const nextButton = document.getElementById('next');
        const pageNumberDisplay = document.getElementById('page-number');

        let currentPage = 1; // Page actuelle
        const pageSize = 8; // Nombre d'éléments par page
        let totalPages = 1; // Total des pages

        // Fonction pour charger les données
        function loadData(query = '', page = 1) {
            const loader = document.getElementById('loader');
            const tableBody = document.getElementById('table-body');
            const pageNumberDisplay = document.getElementById('page-number');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');

            // Afficher le loader
            loader.style.display = 'block';
            tableBody.innerHTML = ''; // Vider la table pendant le chargement
            fetch(`/dette/searchAjax?query=${query}&page=${page}&size=${pageSize}`)
                .then((response) => response.json())
                .then((data) => {
                    const { items, total } = data;
                    loader.style.display = 'none';

                    // Mise à jour du tableau
                    tableBody.innerHTML = '';
                    if (items.length > 0) {
                        items.forEach((item) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.nom}</td>
                                <td>${item.montant}</td>
                                <td>${item.reste}</td>
                                <td>${item.depot ?? 0}</td>
                                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn btn-xs" style="background-color: ${
                                item.etat === 'payée' ? '#00c292' : '#dc3545'
                            }; color: white;">
                                        ${item.etat}
                                    </button>
                                </td>
                                <td>
    <div class="d-flex justify-content-center align-items-center">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${item.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="notika-icon notika-menu"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${item.id}">
                <li><a class="dropdown-item" href="/dette/${item.id}/paiement">Paiement</a></li>
                <li><a class="dropdown-item" href="/dette/${item.id}/modifier">Modifier</a></li>
                <li><a class="dropdown-item" href="/dette/delete/${item.id}">Supprimer</a></li>
                <li><a class="dropdown-item" href="/dette/detail/${item.id}">Détail</a></li>
            </ul>
        </div>
    </div>
</td>

                            `;
                            tableBody.appendChild(row);
                        });

                        // Mise à jour de la pagination
                        totalPages = Math.ceil(total / pageSize);
                        pageNumberDisplay.textContent = `Page ${currentPage} sur ${totalPages}`;
                        prevButton.disabled = currentPage <= 1;
                        nextButton.disabled = currentPage >= totalPages || totalPages === 1;
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Aucun résultat trouvé</td></tr>';
                        pageNumberDisplay.textContent = 'Page 1 sur 1';
                        prevButton.disabled = true;
                        nextButton.disabled = true;
                    }
                })
                .catch((error) => {
                    console.error('Erreur lors du chargement des données :', error);
                    loader.style.display = 'none'; // Masquer le loader en cas d'erreur

                });
        }

        // Recherche dynamique
        searchInput.addEventListener('keyup', function () {
            const query = searchInput.value.trim();
            currentPage = 1; // Réinitialiser à la première page
            loadData(query, currentPage);
        });

        // Gestion des boutons de pagination
        prevButton.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                loadData(searchInput.value.trim(), currentPage);
            }
        });

        nextButton.addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage++;
                loadData(searchInput.value.trim(), currentPage);
            }
        });

        // Charger les données initiales
        loadData();
    });
</script>

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
