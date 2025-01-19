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

<style>
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        color: #333;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: auto; /* Ne pas fixer à droite par défaut */
        right: 100%; /* Menu aligné à gauche du bouton */
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 0;
        margin: 0;
        list-style: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .dropdown-menu li {
        display: block;
    }

    .dropdown-menu .dropdown-item {
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        color: #333;
        cursor: pointer;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f5f5f5;
    }

    .dropdown-menu .text-danger {
        color: #dc3545;
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
                                    <h2>Tableau de données des dettes des Fournisseurs</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ url('/dettefournisseur/create') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter une dette</button></a>
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
                    <form method="GET" action="{{ url('/dettefournisseur') }}">
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
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dettes as $item)
                                <tr>
                                    <td>{{ $item->nom }}</td>
                                    <td>{{ $item->montant }}</td>
                                    <td>{{ $item->reste }}</td>
                                    <td>{{ $item->depot ?? 0 }}</td>
                                    <td>
                                        <span class="btn btn-xs" style="background-color: {{ $item->etat === 'payée' ? 'green' : 'red' }}; color: white;">
                                            {{ $item->etat }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="custom-dropdown">
                                                <!-- Bouton Dropdown -->
                                                <button class="dropdown-toggle-btn" onclick="toggleDropdown({{ $item->id }})">
                                                    <i class="notika-icon notika-menu"></i>
                                                </button>

                                                <!-- Menu déroulant -->
                                                <ul class="dropdown-menu dropdown-left" id="dropdownMenu{{ $item->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('dette.paidFournisseur', $item->id) }}">Paiement</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('dettefournisseur.edit', $item->id) }}">Modifier</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="{{ route('fournisseur.remove', $item->id) }}">Supprimer</a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $dettes->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')

{{--<script>
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

            loader.style.display = 'block'; // Afficher le loader
            tableBody.innerHTML = ''; // Réinitialiser le tableau

            fetch(`/dettefournisseur/searchAjax?query=${query}&page=${page}&size=${pageSize}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Afficher les données reçues

                    const { items, total } = data;

                    loader.style.display = 'none';

                    if (items.length > 0) {
                        items.forEach(item => {
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
                            <a href="/dettefournisseur/${item.id}/edit">Modifier</a>
                            <a href="/dettefournisseur/${item.id}">Détails</a>
                            <a href="#" onclick="confirmDelete(${item.id})">Supprimer</a>
                        </td>
                    `;
                            tableBody.appendChild(row);
                        });

                        totalPages = Math.ceil(total / pageSize);
                        pageNumberDisplay.textContent = `Page ${currentPage} sur ${totalPages}`;
                        prevButton.disabled = currentPage <= 1;
                        nextButton.disabled = currentPage >= totalPages;
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Aucun résultat trouvé</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données :', error);
                    loader.style.display = 'none';
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

<script>
    function toggleDropdown(id) {
        const menu = document.getElementById(`dropdownMenu${id}`);
        const isVisible = menu.style.display === 'block';
        // Masquer tous les autres menus
        document.querySelectorAll('.dropdown-menu').forEach((dropdown) => {
            dropdown.style.display = 'none';
        });
        // Afficher ou masquer le menu actuel
        menu.style.display = isVisible ? 'none' : 'block';
    }


    // Fermer les menus lorsque l'on clique à l'extérieur
    document.addEventListener('click', function (event) {
        const isDropdownButton = event.target.closest('.dropdown-toggle-btn');
        if (!isDropdownButton) {
            document.querySelectorAll('.dropdown-menu').forEach((dropdown) => {
                dropdown.style.display = 'none';
            });
        }
    });
</script>


@notifyJs
