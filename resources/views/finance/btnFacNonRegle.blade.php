<div class="row gx-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title text-center">Autres factures non-réglées</h5>
            </div>
            <div class="card-body text-center">
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @if(!request()->routeIs('encaissement_cons'))
                    <a href="{{route('encaissement_cons')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-clipboard-line"></i>
                        Consultation
                    </a>
                    @endif
                    @if(!request()->routeIs('encaissement_hos'))
                    <a href="{{route('encaissement_hos')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-clipboard-line"></i>
                        Hospitalisation
                    </a>
                    @endif
                    @if(!request()->routeIs('encaissement_examen'))
                    <a href="{{route('encaissement_examen')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-clipboard-line"></i>
                        Examen
                    </a>
                    @endif
                    @if(!request()->routeIs('encaissement_soinsam'))
                    <a href="{{route('encaissement_soinsam')}}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
                        <i class="ri-clipboard-line"></i>
                        Soins Ambulatoires
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
