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
                                    <h2>Tableau de données clients</h2>
                                    <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3" style="display: flex; align-items: center;">
                            <div class="breadcomb-report">
                                <button data-toggle="tooltip" data-placement="left" title="Télécharger le rapport" class="btn"><i class="notika-icon notika-sent"></i></button>
                            </div>
                            <div class="breadcomb-report">
                                <a href="{{ url('/client/ajout') }}"><button data-toggle="tooltip" data-placement="left" class="btn">Ajouter un client</button></a>
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
                        <form id="search-form">

                                @include('search')

                        </form>
                    </div>

                    <div class="table-responsive">
                        <table id="data-table-basic" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="search-results">
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->nom }}</td>
                                    <td>{{ $client->adresse }}</td>
                                    <td>{{ $client->telephone }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="notika-icon notika-menu"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('client.delete', $client->id) }}">supprimer</a>
                                                    </li>

                                                    <li><a class="dropdown-item" href="{{ route('client.modifier', $client->id) }}">modifier</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="pagination">
                            {{ $clients->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#search-input').on('keyup', function() {
        let searchQuery = $(this).val();
        $('#loading-spinner').show();

        $.ajax({
            url: '{{ route("client.index") }}',
            method: 'GET',
            data: { search: searchQuery },
            success: function(response) {
                $('#loading-spinner').hide();
                $('#search-results').html(response);
            },
            error: function(xhr, status, error) {
                $('#loading-spinner').hide();
                console.error("Erreur AJAX :", error);
                alert("Une erreur s'est produite lors de la recherche.");
            }
        });
    });

</script>


@notifyJs
