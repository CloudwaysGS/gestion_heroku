@include('header')
@notifyCss

<x-notify::notify />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<div class="inbox-area">

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                <div class="inbox-left-sd">

                    <form method="post" action="{{url('/facture2')}}">
                        @csrf

                        <div class="inbox-status">
                            <ul class="inbox-st-nav">
                                <li>
                                    <input type="hidden" name="client_id" value=""> <!-- Champ caché pour forcer l'envoi -->
                                    <select id="client-choices" name="client_id" class="form-control input-sm">
                                        <option value="" disabled selected>Sélectionner un client</option> <!-- Option par défaut vide -->
                                        @foreach($clients as $client)
                                            <option value="{{ $client['id'] }}" {{ old('client_id') == $client['id'] ? 'selected' : '' }}>
                                                {{ $client['nom'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </li>
                                <li>
                                    <input type="hidden" name="nom" value="">
                                    <select id="produit-choices" name="nom" class="form-control input-sm" >
                                        <option value="" disabled selected style="z-index: 1; position: relative;">-- Sélectionnez un produit --</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit['id'] }}" {{ old('nom') == $produit['id'] ? 'selected' : '' }}>
                                                {{ $produit['nom'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nom')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </li>



                                <li>
                                    <input type="hidden" name="nomDetail" value="">
                                    <select id="detail-choices" name="nomDetail" class="form-control input-sm">
                                        <option value="" disabled selected>Veuillez sélectionner un détail du produit</option>
                                        @foreach($details as $item)
                                            <option value="{{ $item->id }}" {{ old('nomDetail') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nomDetail }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('nomDetail')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </li>
                                <li>
                                    <div class="nk-int-st">
                                        <input type="text" name="quantite" value="{{ old('quantite') }}" class="form-control input-sm" placeholder="Entrer la quantité">
                                    </div>
                                    @error('quantite')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </li>



                            </ul>
                        </div>

                        <hr>
                        <div class="compose-ml">
                            <button class="btn" type="submit">Ajouter</button>
                        </div>
                    </form>


                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 custom-width">
                <div class="inbox-text-list sm-res-mg-t-30">

                    <div class="inbox-btn-st-ls btn-toolbar">
                        <div class="btn-group ib-btn-gp active-hook nk-email-inbox">
                            <a href="{{url('facturotheque/create')}}" class="btn btn-default btn-sm" title="Télécharger le rapport">Sauvegarder</a>
                            <a href="{{url('facture')}}" class="btn btn-default btn-sm" title="Télécharger le rapport">Facture</a>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-checked"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-promos"></i></button>
                        </div>
                        <div class="btn-group ib-btn-gp active-hook nk-act nk-email-inbox">
                            <form action="{{ route('factures2.deleteAll') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer toutes les factures ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-default btn-sm"><i class="notika-icon notika-trash"></i></button>
                            </form>
                        </div>
                        <div class="btn-group ib-btn-gp active-hook nk-act nk-email-inbox">
                            <span class="btn btn-default btn-sm total-display">Total: {{ number_format($totalMontants, 2) }} FCFA</span>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table id="facture-table" class="table table-hover table-inbox">
                            <thead>
                            <tr>
                                <th>libelle</th>
                                <th>qte</th>
                                <th>prixUnit</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div id="loader" style="display: none;">Chargement...</div>

                            @forelse ($facture2 as $facture)
                                <tr>
                                    <td>
                                        {{ $facture->nom }}

                                    </td>

                                    <td>
                                        <span
                                            data-original-quantite="{{ $facture->quantite }}">
                                            {{ number_format($facture->quantite, 2) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="editable-price"
                                            data-id="{{ $facture->id }}"
                                            data-original-price="{{ $facture->prix }}">
                                            {{ number_format($facture->prix, 2) }}
                                        </span>
                                    </td>

                                    <td>{{ number_format($facture->montant, 2) }} </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a class="dropdown-item" href="{{ route('facture2.modifier', $facture->id) }}">modifier</a>
                                            <a class="dropdown-item" href="{{url('/facture2', $facture->id)}}">Supprimer</a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucune facture trouvée.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Inbox area End-->

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>

    document.addEventListener('DOMContentLoaded', () => {
        const prices = document.querySelectorAll('.editable-price');

        // Fonction pour gérer l'édition des prix
        prices.forEach(price => {
            price.addEventListener('click', () => handleEdit(price, 'prix'));
        });

        function handleEdit(element, field) {
            const factureId = element.getAttribute('data-id');
            const originalValue = parseFloat(element.getAttribute(`data-original-${field}`));

            const input = document.createElement('input');
            input.type = 'number';
            input.step = '0.01';
            input.value = originalValue.toFixed(2);
            input.className = 'form-control';
            input.style.width = '100px';

            element.replaceWith(input);

            input.addEventListener('blur', () => {
                const newValue = parseFloat(input.value);

                if (isNaN(newValue) || newValue <= 0) {
                    alert('Veuillez saisir une valeur valide.');
                    input.replaceWith(element);
                    return;
                }

                // Envoyer les données au serveur
                fetch(`/facture2/${factureId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ [field]: newValue, isAjax: true }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour la cellule éditée
                            input.replaceWith(element);
                            element.textContent = newValue.toFixed(2);
                            element.setAttribute(`data-original-${field}`, newValue);

                            // Mettre à jour le montant individuel
                            const montantCell = element.closest('tr').querySelector('td:nth-child(4)');
                            montantCell.textContent = data.newMontant.toFixed(2);

                            // Mettre à jour le total global
                            const totalElement = document.querySelector('.total-display');
                            totalElement.textContent = `Total: ${data.totalMontants} FCFA`;
                        } else {
                            alert(data.message || 'Une erreur est survenue.');
                            input.replaceWith(element);
                        }
                    })
                    .catch(err => {
                        alert('Erreur de connexion au serveur.');
                        input.replaceWith(element);
                    });
            });

            input.focus();
        }
    });


</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('produit-choices');
        const choices = new Choices(element, {
            searchEnabled: true, // Activer la recherche
            noResultsText: 'Aucun produit trouvé', // Message si aucun résultat
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('client-choices');
        const choices = new Choices(element, {
            searchEnabled: true, // Activer la recherche
            noResultsText: 'Aucun client trouvé', // Message si aucun résultat
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('detail-choices');
        const choices = new Choices(element, {
            searchEnabled: true, // Activer la recherche
            noResultsText: 'Aucun client trouvé', // Message si aucun résultat
        });
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
<script src="notika/js/vendor/jquery-1.12.4.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="notika/js/bootstrap.min.js"></script>
<!-- wow JS
    ============================================ -->
<script src="notika/js/wow.min.js"></script>
<!-- price-slider JS
    ============================================ -->
<script src="notika/js/jquery-price-slider.js"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="notika/js/owl.carousel.min.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="notika/js/jquery.scrollUp.min.js"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="notika/js/meanmenu/jquery.meanmenu.js"></script>
<!-- counterup JS
    ============================================ -->
<script src="notika/js/counterup/jquery.counterup.min.js"></script>
<script src="notika/js/counterup/waypoints.min.js"></script>
<script src="notika/js/counterup/counterup-active.js"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="notika/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- jvectormap JS
    ============================================ -->
<script src="notika/js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="notika/js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="notika/js/jvectormap/jvectormap-active.js"></script>
<!-- sparkline JS
    ============================================ -->
<script src="notika/js/sparkline/jquery.sparkline.min.js"></script>
<script src="notika/js/sparkline/sparkline-active.js"></script>
<!-- sparkline JS
    ============================================ -->
<script src="notika/js/flot/jquery.flot.js"></script>
<script src="notika/js/flot/jquery.flot.resize.js"></script>
<script src="notika/js/flot/curvedLines.js"></script>
<script src="notika/js/flot/flot-active.js"></script>
<!-- knob JS
    ============================================ -->
<script src="notika/js/knob/jquery.knob.js"></script>
<script src="notika/js/knob/jquery.appear.js"></script>
<script src="notika/js/knob/knob-active.js"></script>
<!--  wave JS
    ============================================ -->
<script src="notika/js/wave/waves.min.js"></script>
<script src="notika/js/wave/wave-active.js"></script>
<!--  todo JS
    ============================================ -->
<script src="notika/js/todo/jquery.todo.js"></script>
<!-- plugins JS
    ============================================ -->
<script src="notika/js/plugins.js"></script>
<!--  Chat JS
    ============================================ -->
<script src="notika/js/chat/moment.min.js"></script>
<script src="notika/js/chat/jquery.chat.js"></script>
<!-- main JS
    ============================================ -->
<script src="notika/js/main.js"></script>
<!-- tawk chat JS
    ============================================ -->
{{--<script src="notika/js/tawk-chat.js"></script>--}}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script src="js/vendor/jquery-1.12.4.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="js/bootstrap.min.js"></script>
<!-- wow JS
    ============================================ -->
<script src="js/wow.min.js"></script>
<!-- price-slider JS
    ============================================ -->
<script src="js/jquery-price-slider.js"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="js/owl.carousel.min.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="js/jquery.scrollUp.min.js"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="js/meanmenu/jquery.meanmenu.js"></script>
<!-- counterup JS
    ============================================ -->
<script src="js/counterup/jquery.counterup.min.js"></script>
<script src="js/counterup/waypoints.min.js"></script>
<script src="js/counterup/counterup-active.js"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- sparkline JS
    ============================================ -->
<script src="js/sparkline/jquery.sparkline.min.js"></script>
<script src="js/sparkline/sparkline-active.js"></script>
<!-- flot JS
    ============================================ -->
<script src="js/flot/jquery.flot.js"></script>
<script src="js/flot/jquery.flot.resize.js"></script>
<script src="js/flot/flot-active.js"></script>
<!-- knob JS
    ============================================ -->
<script src="js/knob/jquery.knob.js"></script>
<script src="js/knob/jquery.appear.js"></script>
<script src="js/knob/knob-active.js"></script>
<!--  Chat JS
    ============================================ -->
<script src="js/chat/jquery.chat.js"></script>
<!--  todo JS
    ============================================ -->
<script src="js/todo/jquery.todo.js"></script>
<!--  wave JS
    ============================================ -->
<script src="js/wave/waves.min.js"></script>
<script src="js/wave/wave-active.js"></script>
<!-- plugins JS
    ============================================ -->
<script src="js/plugins.js"></script>
<!-- Data Table JS
    ============================================ -->
<script src="js/data-table/jquery.dataTables.min.js"></script>
<script src="js/data-table/data-table-act.js"></script>
<!-- main JS
    ============================================ -->
<script src="js/main.js"></script>
<!-- tawk chat JS
    ============================================ -->
<script src="js/tawk-chat.js"></script>

@notifyJs
</html>
</body>

