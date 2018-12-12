@extends('layouts.app')
@section('title', 'Add new download')
@section('content')
    <div class='container py-4'>
        <div class='row justify-content-center'>
            <div class='col-md-10'>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class='card'>
                    <div class='card-header'>Add new download</div>
                    <div class='card-body'>
                        <form method="post" action="{{ route('store') }}">
                            {{ csrf_field() }}
                            <div class='form-group'>
                                <label for='url'>Url to resource</label>
                                <textarea rows="5" id='url' class='form-control' name='url'></textarea>
                            </div>
                            <button type='submit' class='btn btn-primary'>Download</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection