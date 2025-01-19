<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-5">
    <a href="{{url('accueille')}}" class="text-left mb-4 font-weight-bold text-primary">Retour</a>

    <h2 class="text-center mb-4 font-weight-bold text-primary">Tableau des Ventes Totales</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover shadow-lg rounded-lg overflow-hidden">
            <thead class="thead-light">
            <tr>
                <th class="text-center">Date</th>
                <th class="text-center">Total des Ventes</th>
                <th class="text-center">Vente Maximale</th>
                <th class="text-center">Vente Minimale</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesData as $key => $data)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                    <td class="text-center text-success font-weight-bold">{{ number_format($data['total'], 2) }} FCFA</td>
                    <td class="text-center text-success font-weight-bold">{{ number_format($data['max'], 2) }} FCFA</td>
                    <td class="text-center text-danger font-weight-bold">{{ number_format($data['min'], 2) }} FCFA</td>
                    
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

