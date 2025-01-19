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
                                    <h2>Tableau de données mes factures</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                                <div class="breadcomb-report">
                                    <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                                </div>
                                <div class="breadcomb-report">
                                    <a href="{{ url('/facture') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter une facture</button></a>
                                </div>
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
                    <form method="GET" action="{{ url('/mesfacture') }}">
                        @include('search')
                    </form>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nbre</th>
                                <th>NumFacture</th>
                                <th>Nom</th>
                                <th>Total</th>
                                <th>Avance</th>
                                <th>Reste</th>
                                <th>Etat</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">

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

        let currentPage = 1;
        const pageSize = 10;
        let totalPages = 1;

        function loadData(query = '', page = 1) {
            fetch(`/mesfacture/searchAjax?query=${query}&page=${page}&size=${pageSize}`)
                .then(response => response.json())
                .then(data => {
                    const { items, total } = data;
                    tableBody.innerHTML = '';
                    if (items.length > 0) {
                        items.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.nbreLigne}</td>
                                <td>${item.numFacture}</td>
                                <td>${item.nomCient}</td>
                                <td>${item.total}</td>
                                <td>${item.avance ?? 0}</td> <!-- Si null, affiche 0 -->
                                <td>${item.reste ?? 0}</td>  <!-- Si null, affiche 0 -->
                                <td>
                            <button class="btn btn-xs" style="background-color: ${
                                item.etat === 'payée' ? '#00c292' : '#dc3545'
                            }; color: white;">${item.etat}</button>
                        </td>
                                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${item.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="notika-icon notika-menu"></i>
            </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="/facturotheque/payer/${item.id}">Payer</a></li>
                                            <li><a href="/facturotheque/modifier/${item.id}">Modifier</a></li>
                                            <li><a href="/facturotheque/export-pdf/${item.id}/">Télécharger</a></li>
                                            <li><a href="/facturotheque/exportTicket-pdf/${item.id}/">Ticket de caisse</a></li>
                                            <li><a href="/facturotheque/acompte/${item.id}">Avance</a></li>
                                            <li><a href="/facturotheque/delete/${item.id}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">Supprimer</a></li>
                                        </ul>
                                    </div>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });

                        totalPages = Math.ceil(total / pageSize);
                        pageNumberDisplay.textContent = `Page ${currentPage} sur ${totalPages}`;
                        prevButton.disabled = currentPage === 1;
                        nextButton.disabled = currentPage === totalPages;
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Aucun résultat trouvé</td></tr>';
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }

        searchInput.addEventListener('keyup', function () {
            currentPage = 1;
            loadData(searchInput.value.trim(), currentPage);
        });

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

        loadData();
    });
</script>


@notifyJs
