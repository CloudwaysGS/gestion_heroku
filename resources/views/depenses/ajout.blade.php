<base href="/public">
@include('header')

    <div class="container mt-5">

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="breadcomb-wp">
                    <div class="breadcomb-icon">
                        <i class="notika-icon notika-windows"></i>
                    </div>
                    <div class="breadcomb-ctn">
                        <h2>Ajouter une dépense</h2>
                        <p>Bienvenue sur le <span class="bread-ntd">modèle d'administration</span> Coulibaly</p>
                    </div>
                </div>
            </div>
            

        </div>

        {{--@if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif--}}

        <form action="{{ route('expenses.store') }}" method="POST" class="mt-4">
            @csrf

            <div class="form-example-int form-horizental">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <label class="hrzn-fm">Montant (FCFA):</label>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <div class="nk-int-st">
                                <input type="number" id="amount" name="amount" class="form-control" placeholder="Ex : 5000" value="{{ old('amount') }}" >
                            </div>
                            @error('amount')
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
                            <label class="hrzn-fm">Catégorie:</label>
                        </div>

                        <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                            <div class="nk-int-st">
                                <select id="detail-choices" name="category" class="form-control input-sm">
                                    <option value="" disabled selected>Sélectionnez une catégorie</option>
                                    <option value="Décaissement">Décaissement</option>
                                    <option value="Logement">Logement</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Santé">Santé</option>
                                    <option value="Éducation">Éducation</option>
                                    <option value="Loisirs">Loisirs</option>
                                    <option value="Vêtements">Vêtements</option>
                                    <option value="Technologie">Technologie</option>
                                    <option value="Assurances">Assurances</option>
                                    <option value="Impôts et taxes">Impôts et taxes</option>
                                    <option value="Épargne et investissements">Épargne et investissements</option>
                                    <option value="Donations et cadeaux">Donations et cadeaux</option>
                                    <option value="Frais bancaires">Frais bancaires</option>
                                    <option value="Autres">Autres</option>
                                </select>
                            </div>
                            @error('category')
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
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@include('footer')
