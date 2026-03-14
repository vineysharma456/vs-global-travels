<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Confirmation</title>
  <style>
    body { margin:0; padding:0; background:#f0f3fb; font-family:'Segoe UI',Arial,sans-serif; }
    .wrap { max-width:560px; margin:40px auto; background:#fff; border-radius:16px;
            box-shadow:0 6px 30px rgba(13,39,80,.10); overflow:hidden; }
    .header { background:#0d2750; padding:28px 32px; }
    .header h1 { margin:0; color:#fff; font-size:1.35rem; font-weight:700; }
    .header p  { margin:4px 0 0; color:#a8bcd4; font-size:0.88rem; }
    .body { padding:28px 32px; }
    .greeting { font-size:1rem; color:#2d3748; margin-bottom:20px; }
    .row { display:flex; justify-content:space-between; padding:10px 0;
           border-bottom:1px solid #edf0f8; font-size:0.9rem; }
    .row:last-child { border-bottom:none; }
    .row .lbl { color:#718096; font-weight:600; }
    .row .val { color:#1a202c; font-weight:500; text-align:right; }
    .badge { display:inline-block; background:rgba(79,110,247,.1); color:#4f6ef7;
             border-radius:50px; padding:2px 12px; font-size:0.82rem; font-weight:700; }
    .note { margin-top:22px; background:#f7f9ff; border-left:4px solid #4f6ef7;
            border-radius:6px; padding:12px 16px; font-size:0.85rem; color:#4a5568; }
    .footer { background:#f7f9ff; padding:18px 32px; text-align:center;
              font-size:0.78rem; color:#a0aec0; }
  </style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>✈ Booking Request Received</h1>
    <p>We'll be in touch shortly to confirm your flight.</p>
  </div>

  <div class="body">
    <p class="greeting">Hi <strong>{{ $booking->name }}</strong>,<br>
      Thank you for your booking request. Here's a summary of what we received:
    </p>

    <div class="row">
      <span class="lbl">Trip Type</span>
      <span class="val"><span class="badge">{{ $booking->trip_type_label }}</span></span>
    </div>
    <div class="row">
      <span class="lbl">Route</span>
      <span class="val">{{ $booking->from_airport }} → {{ $booking->to_airport }}</span>
    </div>
    <div class="row">
      <span class="lbl">Departure</span>
      <span class="val">{{ $booking->departure_date->format('D, d M Y') }}</span>
    </div>
    @if($booking->return_date)
    <div class="row">
      <span class="lbl">Return</span>
      <span class="val">{{ $booking->return_date->format('D, d M Y') }}</span>
    </div>
    @endif
    <div class="row">
      <span class="lbl">Class</span>
      <span class="val">{{ $booking->class_label }}</span>
    </div>
    <div class="row">
      <span class="lbl">Contact</span>
      <span class="val">{{ $booking->country_code }} {{ $booking->contact_number }}</span>
    </div>
    <div class="row">
      <span class="lbl">Booking ID</span>
      <span class="val">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="note">
      Our team will review your request and contact you on the number provided to finalise your ticket.
    </div>
  </div>

  <div class="footer">
    © {{ date('Y') }} Your Travel Company · You received this because you submitted a booking request.
  </div>

</div>
</body>
</html>