<base href="/public">
@include('header')

<style>
    .badge {
        padding: 0.5rem 0.5rem;
        font-size: 0.9rem;
        border-radius: 0.50rem;
    }
    .bg-success {
        background-color: #00c292;
        color: white;
    }
    .bg-danger {
        background-color: #dc3545;
        color: white;
    }

</style>
    <div class="container mt-5">
        <h1 class="text-center"><strong>Détails de la Dette</strong></h1>
        <div class="card shadow mt-4">
            <div class="card-body text-center">
                <table class="table table-bordered table-striped text-center">
                    <tbody>
                    <tr>
                        <td><strong>Nom</strong></td>
                        <td>{{ $dette->nom }}</td>
                    </tr>
                    <tr>
                        <td><strong>Montant de la Dette</strong></td>
                        <td>{{ number_format($dette->montant, 2, ',', ' ') }} F CFA</td>
                    </tr>
                    <tr>
                        <td><strong>Reste</strong></td>
                        <td>{{ number_format($dette->reste, 2, ',', ' ') }} F CFA</td>
                    </tr>
                    <tr>
                        <td><strong>Dépôt</strong></td>
                        <td>{{ number_format($dette->depot, 2, ',', ' ') }} F CFA</td>
                    </tr>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td>{{ $dette->commentaire ?? 'Aucune description disponible' }}</td>
                    </tr>
                    <tr>
                        <td><strong>État</strong></td>
                        <td>
                            <span class="badge" style="background-color: {{ $dette->etat === 'payée' ? '#00c292' : '#dc3545' }};">
                            </span>
                            {{ ucfirst($dette->etat) }}
                        </td>

                    </tr>
                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <a href="{{ route('dette.liste') }}" class="btn" style="background-color: #00c292; color: white;">Retour à la liste</a>
                </div>

            </div>
        </div>
    </div>


@include('footer')
