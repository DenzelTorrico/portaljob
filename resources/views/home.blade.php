@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="card-body">
                    <h3 class="text-primary">Ofertas de trabajo</h3>
                  
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <listjob-component></listjob-component>
                </div>
            
        </div>
    </div>
</div>
@endsection
