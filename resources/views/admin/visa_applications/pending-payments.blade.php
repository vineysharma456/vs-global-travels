@extends('layouts.sidenav')

@section('title', 'Visa Applications')

@section('content')

<style>
    .app-card {
    background: #f5f0f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    border-left: 12px solid #0d2750;
    margin-left: 120px;
    width: 100%;        /* full width (you can change to fixed like 500px) */
    max-width: 700px;   /* limit width */
    min-height: 120px;  /* fixed minimum height */
}

    .app-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .pending { background: #ffe5e5; color: #cc0000; }
    .approved { background: #e6ffed; color: #006622; }

    .traveller-card {
        background: #f8f9fc;
        border-radius: 8px;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid #ddd;
    }

    .doc-list {
        margin-top: 8px;
        padding-left: 18px;
    }

    .doc-list li {
        margin-bottom: 5px;
    }

    .doc-link {
        color: #cc00cc;
        font-weight: 600;
        text-decoration: none;
    }

    .doc-link:hover {
        text-decoration: underline;
    }
</style>

<div class="page-wrap">

    <h2 style="margin-bottom:20px; margin-left:120px;">Visa Applications</h2>

    @forelse($applications as $appId => $rows)

        @php
            $first = $rows->first();
            $travellers = $rows->groupBy('traveller_id');
        @endphp

        <div class="app-card">

            {{-- Application Header --}}
            <div class="app-header">
                <div>
                    <h3 style="margin:0;">
                        {{ $first->application_ref }}
                    </h3>
                    <small style="color:#666;">
                        {{ $first->country_name ?? 'N/A' }}
                    </small>
                </div>

                <span class="badge {{ $first->payment_status == 'pending' ? 'pending' : 'approved' }}">
                    {{ $first->payment_status }}
                </span>
            </div>

            <hr>

            {{-- Travellers --}}
            @foreach($travellers as $travellerId => $tRows)

                @php $t = $tRows->first(); @endphp

                <div class="traveller-card">

                    <strong>Name:</strong> {{ $t->full_name ?? '-' }} <br>
                    <strong>Passport:</strong> {{ $t->passport_number ?? '-' }}

                    {{-- Documents --}}
                    <div style="margin-top:10px;">
                        <strong>Documents:</strong>

                        <ul class="doc-list">
                            @forelse($tRows as $doc)
                                @if($doc->doc_type)
                                    <li>
                                        {{ $doc->doc_type }} -
                                        <a href="{{ url('admin/visa-file/'.$doc->file_path) }}" 
                                           target="_blank" 
                                           class="doc-link">
                                            View File
                                        </a>
                                    </li>
                                @endif
                            @empty
                                <li>No documents found</li>
                            @endforelse
                        </ul>
                    </div>

                </div>

            @endforeach

        </div>

    @empty
        <div style="text-align:center; padding:20px;">
            No Applications Found
        </div>
    @endforelse

</div>

@endsection