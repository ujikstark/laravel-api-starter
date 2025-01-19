<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\CreateTodo;
use App\Jobs\DeleteTodo;
use App\Jobs\UpdateTodo;
use App\Models\Todo;
use App\Traits\Jobs;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    use Jobs;

    /**
     * Display a listing of the resource.
     *      
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userId = auth()->check() ? auth()->id() : null;

        $todos = Todo::where('user_id', $userId)->paginate(5);

        return TodoResource::collection($todos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $todo = $this->dispatch(new CreateTodo($request));

        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = Todo::find($id);

        return new TodoResource($todo);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo = $this->dispatch(new UpdateTodo($todo, $request));

        return new TodoResource($todo->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $this->dispatch(new DeleteTodo($todo));

        return response()->noContent();
    }
}
