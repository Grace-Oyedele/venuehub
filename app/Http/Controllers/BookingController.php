<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Models\VenueBooking;
use App\Notifications\BookingNotification;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use ApiResponser;
    public function index(Request  $request)
    {
        $user = $request->user();
        $bookings = VenueBooking::query()
            ->where(function ($query) use ($user) {
                if ($user->role == "user") {
                    $query->where("user_id", $user->id);
                }
            })->get();
        return $this->success($bookings);
    }
    public function book(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            "venue_id" => "required|exists:venues,id",
            "date" => "required|date|after:tomorrow"
        ]);
        $venue = Venue::where("id", $data["venue_id"])->first();

        if ($venue->status == Venue::BOOKED) {
            return $this->error("Venue not available");
        }

        if ($venue->verified == Venue::PENDING) {
            return $this->error("Venue not verified");
        }

        $booking = VenueBooking::create([
            "venue_id" => $venue->id,
            "user_id" => $user->id,
            "amount" => $venue->price,
            "date" => $data["date"]
        ]);

        if (!$booking) {
            return $this->error("Unable to book venue");
        }

        $venue->status = Venue::BOOKED;
        $venue->save();
        $this->sendBookingEmail($booking);
        return $this->success($booking->load("venue"), "Venue booked");
    }

    private function sendBookingEmail(VenueBooking $booking)
    {
        try {
            $booking->user->notify(new BookingNotification($booking));
        }catch (\Exception $exception){
            logger($exception);
        }

    }
}
