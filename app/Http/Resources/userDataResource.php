
<?php

namespace App\Http\Resources;

use App\Models\delivery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $country1 = delivery::find(data_get($this, 'addressCountry'));
        $country2 = delivery::find(data_get($this, 'addressCountry2'));

        $profileImage = data_get($this, 'profileImage');

        return [
            'data' => [
                'user_id' => data_get($this, 'user_id'),
                'password' => data_get($this, 'password'),

                'profileImage' => !empty($profileImage)
                    ? env('API_BASE_LINK') . '/uploads/profile_photo/' . $profileImage
                    : null,

                'firstName' => data_get($this, 'firstName'),
                'lastName' => data_get($this, 'lastName'),
                'phonenum' => data_get($this, 'phonenum'),
                'email' => data_get($this, 'email'),

                // User address 1
                'addressCountry' => data_get($this, 'addressCountry'),
                'addressCountryName' => $country1?->name,
                'addresscity' => data_get($this, 'addresscity'),
                'addressstreet' => data_get($this, 'addressstreet'),
                'addressbuildingNumber' => data_get($this, 'addressbuildingNumber'),
                'addressfloorNumber' => data_get($this, 'addressfloorNumber'),
                'addressApartmentNumber' => data_get($this, 'addressApartmentNumber'),
                'disSign' => data_get($this, 'disSign'),

                // User address 2
                'addressCountry2' => data_get($this, 'addressCountry2'),
                'addressCountry2Name' => $country2?->name,
                'addresscity2' => data_get($this, 'addresscity2'),
                'addressstreet2' => data_get($this, 'addressstreet2'),
                'addressbuildingNumber2' => data_get($this, 'addressbuildingNumber2'),
                'addressfloorNumber2' => data_get($this, 'addressfloorNumber2'),
                'addressApartmentNumber2' => data_get($this, 'addressApartmentNumber2'),
                'disSign2' => data_get($this, 'disSign2'),

                'gender' => data_get($this, 'gender'),
                'birthDate' => data_get($this, 'birthDate'),
            ],

            'message' => 'user info has been fetched successfully',
            'status' => 'success',
            'code' => 200,
        ];
    }
}
