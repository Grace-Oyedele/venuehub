<?php

namespace App\Http\Controllers;

use App\Models\VenueOwner;
use Illuminate\Http\Request;

class VenueOwnerController extends Controller
{
    public function index()
    {
        $venueOwners = VenueOwner::all();
        return $this->success(['data' => $venueOwners]);

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'verified' => 'required|string',
        ]);
        $validatedData["password"] = bcrypt($validatedData["password"]);
        $venueOwner = VenueOwner::create($validatedData);
        $response = ['data' => $venueOwner, 'message' => 'Venue Owner Created'];
        return $this->success($response);
    }

    public function show($id)
    {
        $venueOwner = VenueOwner::where('id', $id);
        if (empty($venueOwner)) {
            return $this->error('not found', 404);
        }
        return $this->success(['data' => $venueOwner]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'state' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'verified' => 'required|string',
        ]);

        $venueOwner = VenueOwner::where('id', $id)->first();
        if (empty($venueOwner)) {
            return $this->error('not found', 404);
        }
        return $this->success(['data' => $venueOwner]);

    }

    public function destroy($id)
    {
        $venueOwner = VenueOwner::where('id', $id)->first();
        if (empty($venueOwner)) {
            return $this->error('not found', 404);
        }

        $venueOwner->delete();
        return $this->success(['data' => 'Venue Owner Deleted']);

    }
}

