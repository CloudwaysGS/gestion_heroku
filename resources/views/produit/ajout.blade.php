@include('header')
<form action="{{ url('/produit/ajout') }}" method="post">
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
                                        <label class="hrzn-fm">Nom du produit:</label>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                        <div class="nk-int-st">
                                            <input type="text" name="nom" value="{{ old('nom') }}" class="form-control input-sm" placeholder="Enter le nom du produit">
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
                                            <input type="text" name="qteProduit" value="{{ old('qteProduit') }}" class="form-control input-sm" placeholder="Entrer la quantité">
                                        </div>
                                        @error('qteProduit')
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
                                        <label class="hrzn-fm">Prix du produit :</label>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                        <div class="nk-int-st">
                                            <input type="text" name="prixProduit" value="{{ old('prixProduit') }}" class="form-control input-sm" placeholder="Entrer le prix du produit">
                                        </div>
                                        @error('prixProduit')
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
                                        <label class="hrzn-fm">Prix d'achat du produit :</label>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                        <div class="nk-int-st">
                                            <input type="text" name="prixAchat" value="{{ old('prixAchat') }}" class="form-control input-sm" placeholder="Prix d'achat du produit">
                                        </div>
                                        @error('prixAchat')
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
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                        <div class="nk-int-st">
                                            <div class="panel panel-collapse notika-accrodion-cus">
                                                <div class="panel-heading" role="tab">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordionPink" href="#accordionPink-three" aria-expanded="false">
                                                            Souhaitez-vous ajouter un détail au produit ?
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="accordionPink-three" class="collapse" role="tabpanel">
                                                    <div class="panel-body">
                                                        <div class="form-example-int form-horizental">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                                                        <label class="hrzn-fm">Nom du produit en détail :</label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                                                        <div class="nk-int-st">
                                                                            <input type="text" name="nomDetail" value="{{ old('nomDetail') }}" class="form-control input-sm" placeholder="Enter Nom du produit en détail">
                                                                        </div>
                                                                        @error('nomDetail')
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
                                                                        <label class="hrzn-fm">Prix du détail :</label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                                                        <div class="nk-int-st">
                                                                            <input type="text" name="prixDetail" value="{{ old('prixDetail') }}" class="form-control input-sm" placeholder="Enter le prix du détail">
                                                                        </div>
                                                                        @error('prixDetail')
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
                                                                        <label class="hrzn-fm">Nombre :</label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                                                        <div class="nk-int-st">
                                                                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control input-sm" placeholder="Enter le nom du produit">
                                                                        </div>
                                                                        @error('nombre')
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
@include('footer')
