<?php


namespace App\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Models\Todo;

class DeleteTodo extends Job implements ShouldDelete
{
    public function handle(): Todo
    {
        \DB::transaction(function () {

            $this->model->delete();
        });

        return $this->model;
    }
}
