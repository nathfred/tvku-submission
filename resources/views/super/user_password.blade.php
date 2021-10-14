@extends('layouts.super.app')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <div class="row">
                        <div class="col">
                            <h3>Profil Pegawai</h3>
                        </div>
                    </div>
                    <p class="text-subtitle text-muted">Profil Diri & Data Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Password</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="{{ route('super-save-user-password', ['id' => $user->id]) }}">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="email" id="email" class="form-control"
                                                    name="email" value="{{ $user->email }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Password Lama (Hashed)</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="prev_password" class="form-control"
                                                    name="prev_password" value="{{ $user->password }}" disabled>
                                            </div>
                                            <div class="col-md-4 mt-5">
                                                <label>Password Baru</label>
                                            </div>
                                            <div class="col-md-8 form-group mt-5">
                                                <input type="password" id="password" class="form-control"
                                                    name="password" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Password Confirm</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="password" id="password_confirmation" class="form-control"
                                                    name="password_confirmation" value="" required >
                                            </div>
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Confirm</button>
                                            </div>
                                            @if ($errors->any())
                                                <div class="alert alert-danger mt-4">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body py-4 px-5">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    @if ($user->gender == "male")
                                        <img src="{{ asset('images/faces/male') }}.png">
                                        {{-- <i class="fas fa-grin-alt" style="width:50px; height:50px;"></i> --}}
                                    @elseif ($user->gender == "female")
                                        <img src="{{ asset('images/faces/female') }}.png">
                                        {{-- <i class="fas fa-smile-beam" style="width:50px; height:50px;"></i> --}}
                                    @else
                                        <img src="{{ asset('images/faces/male') }}.png">
                                        {{-- <i class="fas fa-laugh" style="width:50px; height:50px;"></i> --}}
                                    @endif
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ $user->name }}</h5>
                                    <!--<h6 class="text-muted mb-0">{{ $user->email }}</h6>-->
                                    <button class="btn btn-primary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"aria-haspopup="true" aria-expanded="false">{{ $user->email }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@include('super.alerts')

@endsection