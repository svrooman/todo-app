<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('todocur')
            ->only(
                [
                    'store',
                    'update',
                ]
            );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $endOfToday = (new DateTime())->setTime(23,59,59)->format('Y-m-d H:i:s');

        return response()->json(
            Todo::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ToDo::class);

        return response()->json(
            Todo::create($request->all())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = ToDo::find($id);

        $this->authorize('update', $todo);

        foreach($request->all() as $param => $data) {
            $todo->{$param} = $data;
        }

        $todo->save();

        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', ToDo::find($id));

        return ToDo::destroy($id);
    }
}
