<base href="/public">
@include('header')
<form action="{{ route('expenses.update', $expense->id) }}" method="POST">
    @csrf
    @method('PUT')    <!-- Champ Montant -->
    <div class="form-example-int form-horizental">
        <div class="form-group">
            <div class="row">
                <label for="amount" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 hrzn-fm">
                    Montant (FCFA):
                </label>
                <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                    <div class="nk-int-st">
                        <input type="number" id="amount" name="amount" class="form-control"
                               placeholder="Ex : 5000" value="{{ old('amount', $expense->amount) }}" min="0" step="0.01" required>

                    </div>
                    @error('amount')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Champ Catégorie -->
    <div class="form-example-int form-horizental">
        <div class="form-group">
            <div class="row">
                <label for="category" class="col-lg-2 col-md-3 col-sm-3 col-xs-12 hrzn-fm">
                    Catégorie:
                </label>
                <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                    <div class="nk-int-st">
                        <select id="category" name="category" class="form-control input-sm" required>
                            <option value="" disabled selected>Sélectionnez une catégorie</option>
                            @foreach(['Décaissement', 'Logement', 'Transport', 'Santé', 'Éducation', 'Loisirs',
                                      'Vêtements', 'Technologie', 'Assurances', 'Impôts et taxes',
                                      'Épargne et investissements', 'Donations et cadeaux',
                                      'Frais bancaires', 'Autres'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $expense->category) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Ajouter -->
    <div class="form-example-int mg-t-15">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12"></div>
            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                <button type="submit" class="btn btn-success notika-btn-success"
                        style="background-color: #00c292; color: white;">
                    Modifier
                </button>
            </div>
        </div>
    </div>
</form>

@include('footer')
