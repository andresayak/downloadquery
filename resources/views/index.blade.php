@extends('layouts.app')
@section('title', 'Downloads')
@section('content')
    <div class='card'>
        <div class='card-header'>All downloads</div>
        <div class='card-body'>
            <a href="{{ route('create') }}" class='btn btn-primary btn-sm mb-3'>
                Add URL
            </a>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Url</th>
                    <th>Status</th>
                    <th>Size Mb</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($downloads as $download)
                    <tr>
                        <td>{{ $download->id }}</td>
                        <td style="word-break:break-all;"><a target="_blank" href='{{ $download->url }}'>{{ $download->url }}</a></td>
                        {{--<td>{{ $download->getStatus() }}</td>--}}
                        <td>
                            {{ $download->status }}
                            @if($download->error_msg)
                                <br>
                                {{ $download->error_msg }}
                            @endif
                        </td>
                        <td>{{ $download->getSize()?? '- -' }}</td>
                        <td>{{ $download->created_at }}</td>
                        <td><a href="{{ route('download', ['id'=> $download->id]) }}" class="btn btn-primary btn-sm mb-3">Download</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection