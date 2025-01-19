<base href="/public">
@include('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<form action="{{ route('depotEntree.store') }}" method="post">
    @csrf
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-example-wrap mg-t-30">
                <div class="cmp-tb-hd cmp-int-hd">
                    <h2>Ajouter un produit</h2>
                </div>

                <div class="form-example-int form-horizental">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                <label class="hrzn-fm">Choisir un produit:</label>
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <div class="nk-int-st">
                                    <select id="produit-choices" name="nom" class="form-control input-sm">
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit['id'] }}" {{ old('nom') == $produit['id'] ? 'selected' : '' }}>
                                                {{ $produit['nom'] }}
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
                                <label class="hrzn-fm">Quantité du produit :</label>
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <div class="nk-int-st">
                                    <input type="text" name="qteEntree" value="{{ old('qteEntree') }}" class="form-control input-sm" placeholder="Entrer la quantité">
                                </div>
                                @error('qteEntree')
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
                            <button class="btn btn-success notika-btn-success" style="background-color: #00c292; color: white;">
                                Enrégistrer
                            </button>
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
            placeholderValue: 'Sélectionner un produit', // Placeholder
            noResultsText: 'Aucun produit trouvé', // Message si aucun résultat
        });
    });
</script>
@include('footer')
