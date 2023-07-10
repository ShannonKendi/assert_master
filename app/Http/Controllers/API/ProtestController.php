<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\protests;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;



class ProtestController extends Controller
{
    //
    private $protestRules = [
        'title' => 'required|string|unique:protests,title',
        'event_date' => 'required|date',
        'description' => 'required|string|min:250',
        'venue' => 'required|string'
    ];

    private $customMessages = [
        'required' => 'Cannot be empty',
        'string' => 'Please use alphabet letters',
        'min' => 'Must have a minimum 250 characters',
    ];

    private function generateToken()
    {
        $token = Str::random(16);
        $validToken = Validator::make(['token' => $token], ['token' => 'unique:protests,protest_id']);

        if (!$validToken->passes()) {
            return $this->generateToken();
        }

        return $token;
    }

    private function validate_protest_date($event_date, $current_date)
    {
        $eventDate = Carbon::parse($event_date);
        $currentDate = Carbon::parse($current_date);

        $minDate = $currentDate->copy()->addDays(4);
        $maxDate = $currentDate->copy()->addDays(14);

        if ($eventDate->greaterThanOrEqualTo($minDate) && $eventDate->lessThanOrEqualTo($maxDate)) {
            // Valid protest date
            $expiryDate = $eventDate->copy()->addDays(1);
            return $expiryDate;
        } else {
            // Invalid protest date
            return false;
        }
    }

    private function validateProtestor($id)
    {
        $selected = users::where('id', '=', $id)->first();
        if ($selected->role_name == 'peace usher') {
            return false;
        }

        return true;
    }

    private function alertAuthorities()
    {
        return "Authorities have been alerted";
    }

    //complete
    public function post_protest(Request $protestData)
    {
        if (!($this->validateProtestor($protestData->creator_token))) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => 'You cannot perform this action'
                ]
            ], 400);
        }

        $validatedInput = Validator::make($protestData->all(), $this->protestRules, $this->customMessages);

        //this method checks the timestamps between the day of posting and the (4 - 14 day) time gap
        $validatedInput->after(function ($validator) use ($protestData) {
            $eventDate = $protestData->get('event_date');
            $currentDate = Carbon::now()->format('Y-m-d');

            $validDate = $this->validate_protest_date($eventDate, $currentDate);

            if ($validDate === false) {
                $validator->errors()->add('event_date', 'Invalid protest date. The event date must have a minimum of 4 days and a maximum of 14 days time gap from the current date.');
            }
        });

        if ($validatedInput->fails()) {
            $errors = $validatedInput->errors()->toArray();
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => $errors
                ]
            ], 400);
        }

        $new_protest = protests::create([
            'protest_id' => $this->generateToken(),
            'title' => $protestData->title,
            'event_date' => $protestData->event_date,
            'description' => $protestData->description,
            'venue' => $protestData->venue,
            'is_validated' => false,
            'creator_token' => $protestData->creator_token
        ]);

        if (!$new_protest) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'data' => [
                    'message' => 'Protest could not be posted',
                    'error' => $new_protest
                ]
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Protest has been posted. Awaiting approval...'
        ], 200);
    }

    //complete
    public function delete_protest($protest_id)
    {
        //First the passed id is validated whether it's in the db and the boolean result is stored in a variable
        $selected = protests::find($protest_id);
        if ($selected) {
            //then it's deleted returning a 200 OK response
            $selected->delete();
            return  response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Protest has been deleted successfully.'
            ], 200);
        } else {

            //else an error response is returned
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Protest could not be deleted. Try again later...'
            ], 400);
        }
    }

    public function edit_protest(Request $protestData)
    {
    }

    //complete
    public function get_all_protests()
    {
        //gets all protests
        $protests = protests::all();

        //if none return an error code message of 400
        if ($protests->count() > 0) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'protests' => $protests
            ], 200);
        }
        return  response()->json([
            'status' => 400,
            'success' => false,
            'message' => 'No records found'
        ], 400);
    }

    public function get_user_protests($user_id)
    {
        //First the passed id is validated whether it's in the db and the boolean result is stored in a variable
        $selected = protests::where('creator_token', '=', $user_id)->get();
        if ($selected) {
            //then it's deleted returning a 200 OK response
            return  response()->json([
                'status' => 200,
                'success' => true,
                'data' => ($selected->toArray() != [] ? $selected->toArray() : 'No protests attached to this user profile')
            ], 200);
        } else {

            //else an error response is returned
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Protest could not be found. Try again later...'
            ], 400);
        }
    }

    public function get_specific_protest($protest_id)
    {
        $protest = protests::where('protest_id', '=', $protest_id)->first();
        if (!$protest) {
            //else an error response is returned
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Protest could not be found. Try again later...'
            ], 400);
        }

        return  response()->json([
            'status' => 200,
            'success' => true,
            'data' => $protest
        ], 200);
    }
    public function emergency(Request $protestData)
    {
        $protest = protests::where('protest_id', '=', $protestData->protest_id)->first();
        $new_protest = $protest->update(
            [
                'is_validated' => false
            ]
        );

        if ($new_protest) {
            $alertmsg = $this->alertAuthorities();
            return response()->json(
                [
                    'status' => 200,
                    'message' => $alertmsg
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => 400,
                    'message' => 'Couldnt cancel protest'
                ],
                400
            );
        }
    }
}
