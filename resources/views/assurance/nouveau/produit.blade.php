@extends('app')

@section('titre', 'Nouveau Produit')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Nouveau Produit
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <!-- Row starts -->
    <div class="row justify-content-center">
        <div class="col-lg-4 ">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Formulaire Nouveau Produit</h5>
                </div>
                <form class="card-body" id="form_produit" method="post" action="{{route('insert_produit')}}" >
                    @csrf
                    <!-- Row starts -->
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <input name="pro" required type="text" class="form-control" id="pro" placeholder="Email de l'assurance">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-start">
                                <button type="reset" class="btn btn-outline-danger">
                                    Rémise à zéro
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Row ends -->
                </form>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Assurance enregistrées Aujourd'hui</h5>
                </div>
                <div class="card-body">
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover m-0 truncate">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Produit</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prods as $key => $value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            {{$value->nom}}
                                        </td>
                                        <td>
                                            <div class="d-inline-flex gap-1">
                                                <a class="btn btn-outline-danger btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#delRow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer">
                                                  <i class="ri-delete-bin-line"></i>
                                                </a>
                                                <a class="btn btn-outline-info btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#Mmodif{{$value->id}}">
                                                  <i class="ri-edit-box-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
</div>

<div class="modal fade" id="delRow" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirm
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the department?
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">No</a>
                    <a class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Yes</a>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($prods as $value)
<div class="modal fade" id="Mmodif{{$value->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Mise à jour
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="card-body" id="form_produit_modifier" method="post" action="{{route('update_produit',$value->id)}}" >
                    @csrf
                    <!-- Row starts -->
                    <div class="row gx-3">
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Produit</label>
                                <input name="pro_modif" required type="tel" class="form-control" id="pro_modif" placeholder="Email de l'assurance" value="{{$value->nom}}">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                   Fermer
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Row ends -->
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection