@extends('layouts.sidenav')

@section('title', 'Visa Applications')

@section('content')

<div class="page-wrap">
    <h2 style="margin-bottom:20px;">Visa Applications List</h2>

    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>Application Ref</th>
                <th>Country</th>
                <th>Payment Status</th>
                <th>Travellers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
                <tr>
                    <td>{{ $app->application_ref }}</td>
                    <td>{{ $app->country->country_name ?? 'N/A' }}</td>
                    <td>{{ $app->payment_status }}</td>
                    <td>
                        @foreach($app->travellers as $traveller)
                            <div style="margin-bottom:15px; padding:10px; border:1px solid #ccc;">
                                
                                <strong>Name:</strong> {{ $traveller->full_name }} <br>
                                <strong>Passport:</strong> {{ $traveller->passport_number }} <br>
                                <strong>Email:</strong> {{ $traveller->email }} <br>

                                <br>
                                <strong>Documents:</strong>
                                <ul>
                                    @foreach($traveller->documents as $doc)
                                        <li>
                                            {{ $doc->doc_type }} - 
                                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank">
                                                View File
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection