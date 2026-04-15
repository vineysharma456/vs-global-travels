<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketQueries;
use App\Services\WhatsAppService;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
   
    //    public function bookingStore(Request $request)
    //     {
    //         TicketQuery::create($request->all());

    //         return redirect()->back()->with('success','Booking query submitted successfully');
    //     }

         /**
     * Validate, save, and send confirmation email.
     */
    public function bookingStore(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:120'],
            'email'          => ['required', 'email', 'max:180'],
            'trip_type'      => ['required', 'in:one-way,round-trip'],
            'from'           => ['required', 'string', 'max:10'],
            'to'             => ['required', 'string', 'max:10', 'different:from'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date'    => ['nullable', 'date', 'after_or_equal:departure_date', 'required_if:trip_type,round-trip'],
            'class'          => ['required', 'in:economy,premium_economy,business,first'],
            'country_code'   => ['required', 'string', 'max:10'],
            'contact_number' => ['required', 'string', 'regex:/^[0-9]{7,15}$/'],
        ], [
            'to.different'              => 'Departure and destination airports must be different.',
            'return_date.required_if'   => 'A return date is required for round-trip bookings.',
            'contact_number.regex'      => 'Contact number must be 7–15 digits.',
        ]);

        // Map form fields → model columns
        $booking = TicketQueries::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'trip_type'      => $data['trip_type'],
            'from_airport'   => $data['from'],
            'to_airport'     => $data['to'],
            'departure_date' => $data['departure_date'],
            'return_date'    => $data['return_date'] ?? null,
            'class'          => $data['class'],
            'country_code'   => $data['country_code'],
            'contact_number' => $data['contact_number'],
        ]);

        // Send confirmation email to the user
        // Mail::to($booking->email)->send(new BookingConfirmation($booking));
          // ✅ Format number
    $phone = '+' . $data['country_code'] . $data['contact_number'];

        // ✅ Send TEMPLATE (first message)
        $whatsapp->sendTemplate($phone, [
            "1" => now()->format('d/m'),
            "2" => now()->format('h:i A')
        ]);
        return redirect()->route('booking.success', $booking)
                         ->with('success', 'Booking received! A confirmation has been sent to ' . $booking->email);
    }

    /**
     * Success / confirmation page.
     */
    public function success(TicketQueries $booking)
    {
        return view('booking-confirmation', compact('booking'));
    }
    
}
