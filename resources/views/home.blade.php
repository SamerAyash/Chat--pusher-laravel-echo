@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">We code messenger</div>
                <chat-app :user="{{auth()->user()}}"></chat-app>
                <div class="card-body" style="padding: 0px !important;" id="app">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
