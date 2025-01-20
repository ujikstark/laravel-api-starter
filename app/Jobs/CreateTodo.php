<?php


namespace App\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Todo;

class CreateTodo extends Job implements ShouldCreate
{
    public function handle(): Todo
    {
        \DB::transaction(function () {

            $this->model = Todo::create($this->request->all());
        });

        return $this->model;
    }
}
