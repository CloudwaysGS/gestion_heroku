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
                        {{-- Actions à définir --}}
                    </ul>
                </div>
            </div>
        </td>
    </tr>
@endforeach
