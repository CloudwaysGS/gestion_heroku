<base href="/public">
@include('header')

    <div class="container">
        <h1>Modifier le Fournisseur</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fournisseur.update', $fournisseur->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-example-int form-horizental mg-t-15">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label class="hrzn-fm">Nom:</label>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <div class="nk-int-st">
                                <input type="text" name="nom" id="nom" class="form-control input-sm" value="{{ old('nom', $fournisseur->nom) }}" required>
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
                            <label class="hrzn-fm">Téléphone:</label>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <div class="nk-int-st">
                                <input type="text" name="telephone" id="telephone" class="form-control input-sm" value="{{ old('telephone', $fournisseur->telephone) }}">
                            </div>
                            @error('telephone')
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
                            <label class="hrzn-fm">Adresse:</label>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <div class="nk-int-st">
                                <input type="text" name="adresse" value="{{ old('adresse', $fournisseur->adresse) }}" class="form-control input-sm" >
                            </div>
                            @error('adresse')
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
                        <button class="btn btn-success notika-btn-success">Mettre à jour</button>
                        <a href="{{ route('fournisseur.index') }}" class="btn btn-danger notika-btn-success">Annuler</a>                    </div>
                </div>
            </div>

        </form>
    </div>

@include('footer')
