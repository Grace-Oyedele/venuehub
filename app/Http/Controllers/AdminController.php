<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Notifications\BookingNotification;
use App\Notifications\StatusNotification;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponser;

    public function toggleStatus($venueId)
    {
        $venue = Venue::where("id", $venueId)->first();
        if (empty($venue)) {
            return $this->error("Record not found", 404);
        }
        if ($venue->verified == Venue::PENDING) {
            $verified = Venue::APPROVED;
        }else{
            $verified = Venue::PENDING;
        }

        $venue->verified = $verified;
        $venue->save();
        $this->sendStatusEmail($venue, $verified);
         return $this->success($venue, "Venue verification status updated");

    }

    private function sendStatusEmail(Venue $venue, string $verified)
    {
        try {
            $user = $venue->user;
            $user->notify(new StatusNotification($venue, $verified));
        }catch (\Exception $exception){
            logger($exception);
        }

    }
}
