<base href="/public">
@include('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<form action="{{ url('/dettefournisseur') }}" method="post">
    @csrf
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-example-wrap mg-t-30">
                <div class="cmp-tb-hd cmp-int-hd">
                    <h2>Ajouter une dette</h2>
                </div>
                <div class="form-example-int form-horizental">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <label class="hrzn-fm">Choisir un client:</label>
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <div class="nk-int-st">
                                    <select id="produit-choices" name="fournisseur_id" class="form-control input-sm">
                                        @foreach($fournisseurs as $item)
                                            <option value="{{ $item['id'] }}" {{ old('item_id') == $item['id'] ? 'selected' : '' }}>
                                                {{ $item['nom'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                @error('nom')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-example-int form-horizental mg-t-15">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <label class="hrzn-fm">Montant dette:</label>
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <div class="nk-int-st">
                                    <input type="text" name="montant" value="{{ old('montant') }}" class="form-control input-sm" placeholder="Entrer le montant de la dette">
                                </div>
                                @error('montant')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-example-int form-horizental">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <label class="hrzn-fm">Commentaire:</label>
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <div class="nk-int-st">
                                    <textarea name="commentaire" class="form-control input-sm" placeholder="Entrer la description du dette">{{ old('commentaire') }}</textarea>
                                </div>
                                @error('commentaire')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-example-int mg-t-15">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <button class="btn btn-success notika-btn-success">Enrégistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('produit-choices');
        const choices = new Choices(element, {
            searchEnabled: true, // Activer la recherche
            placeholderValue: 'Sélectionner un fournisseur', // Placeholder
            noResultsText: 'Aucun fournisseur trouvé', // Message si aucun résultat
        });
    });
</script>

@include('footer')
