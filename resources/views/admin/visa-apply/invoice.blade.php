<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            margin-bottom: 5px;
        }
        .section {
            margin-top: 20px;
        }
        .section h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .row {
            margin-bottom: 8px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="invoice-box">

    <h2>🧾 Visa Application Invoice</h2>
    <p><strong>Application ID:</strong> {{ $application->application_ref }}</p>
    {{-- <p><strong>Country:</strong> {{ $application->country->name ?? '-' }}</p> --}}
    <p><strong>Status:</strong> {{ ucfirst($application->payment_status) }}</p>

    
    <div style="text-align:right; margin-bottom:15px;">
            <a href="{{ route('invoice.download', $application->id) }}" target="_blank" 
            style="padding:10px 15px; background:black; color:white; text-decoration:none; border-radius:5px;">
                ⬇ Download Invoice
            </a>
        </div>

    <!-- Travelers -->
    <div class="section">
        <h3>👤 Applicant Details</h3>

        @foreach($application->travelers as $traveler)
            <div style="margin-bottom:15px;">
                <div class="row"><strong>Name:</strong> {{ $traveler->full_name }}</div>
                <div class="row"><strong>Passport:</strong> {{ $traveler->passport_number }}</div>
                <div class="row"><strong>Mobile:</strong> {{ $traveler->mobile ?? '-' }}</div>
                <div class="row"><strong>Email:</strong> {{ $traveler->email ?? '-' }}</div>
                {{-- <div class="row"><strong>Place of Issue:</strong> {{ $traveler->place_of_issue ?? '-' }}</div> --}}
            </div>
        @endforeach
    </div>

    <!-- Payment -->
    <div class="section">
        <h3>💳 Payment Details</h3>
        <p><strong>Payment ID:</strong> {{ $application->visaPayment->razorpay_payment_id ?? '-' }}</p>
        <table class="table">
            <tr>
                <th>Description</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
            <tr>
                <td>Visa Fee</td>
                <td class="text-right">{{ $application->visaPayment->amount ?? 0 }}</td>
            </tr>
            <tr>
                <td>Service Fee</td>
                <td class="text-right">{{ $application->visaPayment->service_fee ?? 0 }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <th class="text-right">
                    ₹{{ ($application->visaPayment->amount ?? 0) + ($application->visaPayment->service_fee ?? 0) }}
                </th>
            </tr>
        </table>

        
    </div>

    <!-- Documents -->
    {{-- <div class="section">
        <h3>📄 Documents Submitted</h3>

        @foreach($application->travelers as $traveler)
            @foreach($traveler->documents as $doc)
                <div class="row">• {{ ucfirst($doc->doc_type) }}</div>
            @endforeach
        @endforeach
    </div> --}}

</div>

</body>
</html>