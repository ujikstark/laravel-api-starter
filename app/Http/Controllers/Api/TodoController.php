<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Jobs\CreateTodo;
use App\Jobs\DeleteTodo;
use App\Jobs\UpdateTodo;
use App\Models\Todo;
use App\Traits\Jobs;
use Illuminate\Http\Request;

class TodoController extends ApiController
{

    use Jobs;

    /**
     * Display a listing of the resource.
     *      
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userId = auth()->check() ? auth()->id() : null;

        $todos = Todo::where('user_id', $userId)->applyFilters($request->all())->paginate(5);

        return TodoResource::collection($todos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $todo = $this->dispatch(new CreateTodo($request));

        return $this->created(route('api.todos.show', $todo->id), new TodoResource($todo));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = Todo::find($id);

        if (! $todo instanceof Todo) {
            return $this->errorInternal('No query results for model [' . Todo::class . '] ' . $id);
        }

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
        try {
            $this->dispatch(new DeleteTodo($todo));
            return $this->noContent();
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
