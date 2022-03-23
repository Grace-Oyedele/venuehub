<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenueImages;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $user = $request->user();
        $venues = Venue::query()
            ->where(function ($query) use ($user) {
                if ($user->role == "user") {
                    $query->where("user_id", $user->id);
                }
            })->get();
        return $this->success($venues);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:venues,name',
            'capacity' => 'required|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'state' => 'required|string',
            "price" => "required|numeric",
            "biddable" => "sometimes|required|integer",
        ]);

        $validatedData["user_id"] = $request->user()->id;
        $venue = Venue::create($validatedData);
        $response = ['data' => $venue, 'message' => 'Venue created successfully'];
        return $this->success($response);
    }


    public function show($id)
    {
        $venue = Venue::where('id', $id)->first();
        if (empty($venue)) {
            return $this->error('Venue not found', 404);
        }

        return $this->success($venue);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|string',
            'address' => 'required|string',
            'location' => 'required|string',
            'state' => 'required|string',
            "price" => "required|numeric",
            "biddable" => "sometimes|required|integer"

        ]);

        $venue = Venue::where('id', $id)->first();
        if (empty ($venue)) {
            return $this->error('Venue not found', 404);
        }
        $venue->update($validatedData);
        $response = ['data' => $venue, 'message' => 'Details updated succesfully.'];
        return $this->success($response);

    }

    public function destroy($id)
    {
        $venue = Venue::where('id', $id)->first();
        if (empty($venue)) {
            return $this->error('Venue not found', 404);
        }

        $venue->delete();
        return $this->success("Venue Deleted");

    }

    public function uploadImages(Request $request)
    {
        $data = $request->validate([
            "venue_id" => "required|exists:venues,id",
            "images" => "required|array",
            "images.*" => "required|file"
        ]);
        $venue = Venue::where("id", $data["venue_id"])->first();
        if (empty($venue)) {
            return $this->error("Venue not found", 404);
        }
        $path = public_path("venueImages");

        $images = $data["images"];

        foreach ($images as $imageFile) {
            if ($imageFile->isValid()) {
                $name = Str::random(10) . "." . $imageFile->getClientOriginalExtension();
                $imageFile->move($path, $name);
                VenueImages::create([
                    "venue_id" => $venue->id,
                    "image" => $name,
                    "is_featured" => false
                ]);
            }
        }
        return $this->success($venue->load("images"), "Venue images uploaded successfully");
    }


    public function makeFeatured($venueImageId)
    {
        $venueImage = VenueImages::where("id", $venueImageId)->first();
        if (empty($venueImage)) {
            return $this->error("Venue image not found", 404);
        }

        //remove all other featured images
        VenueImages::query()
            ->where("venue_id", $venueImage->id)
            ->update([
                "is_featured" => false
            ]);
        $venueImage->is_featured = true;
        $venueImage->save();
        return $this->success($venueImage->load("venue"), "Image made featured");
    }

}

