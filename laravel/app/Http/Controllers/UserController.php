<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    const interval = 1;
    /**
     * Display a listing of users
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // return list of users order by points DESC
            return User::all()->sortByDesc('points');
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error displaying the users.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:users|max:20',
                'age' => 'required|numeric',
                'points' => 'numeric',
                'address' => 'required'
            ]);
            User::create($request->all());

            return response([
                'status' => true,
                'message' => 'User created successfully.'
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error in your request.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $users = User::find($id);

            if (empty($users)) {
                return response([
                    'message' => 'User cannot be found!'
                ], Response::HTTP_NOT_FOUND);
            }
            return $users;
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error in your request.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update user point. It can be either increment or decrement actions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePoints($id, $action)
    {
        if (!in_array($action, ['increment', 'decrement'])) {
            return response([
                'message' => 'Your request endpoint is not valid!'
            ], Response::HTTP_BAD_REQUEST);
        }
        $interval = ($action == 'increment') ? 1 : -1;

        try {
            $userPoints = $this->getUserPoints($id);
            if (is_numeric($userPoints)) {
                DB::table('users')
                    ->where('id', $id)
                    ->update(['points' => $userPoints + $interval]);

                // redirect to list all users api, order by points
                // based on the requirement in assessment test
                return redirect()->action([UserController::class, 'index']);

            } else {
                return response([
                    'message' => 'User does not exist or has invalid points!'
                ], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error in your request.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            // make sure user exists
            $user = User::find($id);
            if ($user) {
                User::destroy($id);
                return response([
                    'status' => true,
                    'message' => 'User ' . $id . ' deleted successfully.'
                ], Response::HTTP_OK);
            } else {
                return response([
                    'message' => 'User cannot be found!'
                ], Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error in your request.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get user points by given user id
     * @param $id
     * @return mixed|void
     */
    private function getUserPoints($id) {

        // make sure user id exists, then get the user points
        try {
            $user = User::find($id);
            if ($user) {
                $points = DB::table('users')
                    ->select('points')
                    ->where('id', $id)
                    ->first();

                return $points->points;
            } else {
                return response([
                    'message' => 'User cannot be found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'error' => $exception->getMessage(),
                'message' => 'There was an error in your request.'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
