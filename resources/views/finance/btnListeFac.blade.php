<div class="row gx-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title text-center">Autres Liste de factures</h5>
            </div>
            <div class="card-body text-center">
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @if(!request()->routeIs('liste_caisse_cons'))
                    <a href="{{route('liste_caisse_cons')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-article-line"></i>
                        Consultation
                    </a>
                    @endif
                    @if(!request()->routeIs('liste_caisse_hos'))
                    <a href="{{route('liste_caisse_hos')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-article-line"></i>
                        Hospitalisation
                    </a>
                    @endif
                    @if(!request()->routeIs('liste_caisse_examen'))
                    <a href="{{route('liste_caisse_examen')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-article-line"></i>
                        Examen
                    </a>
                    @endif
                    @if(!request()->routeIs('liste_caisse_soinsam'))
                    <a href="{{route('liste_caisse_soinsam')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-article-line"></i>
                        Soins Ambulatoires
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
