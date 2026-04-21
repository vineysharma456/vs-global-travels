@extends('layouts.sidenav')

@section('title', 'Admin — Add Country')

@section('content')

<div class="container">
    <h2>Country List</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Country Name</th>
                {{-- <th>Flag</th> --}}
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($countries as $key => $country)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $country->country_name }}</td>
                    {{-- <td>{{ $country->flag_emoji ?? '-' }}</td> --}}
                    <td>
                        <a href="{{ route('admin.country.images', $country->id) }}" class="btn btn-primary btn-sm">
                            Add Images
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        NO Countries found yet
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection