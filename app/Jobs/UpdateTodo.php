<?php


namespace App\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Todo;

class UpdateTodo extends Job implements ShouldUpdate
{
    public function handle(): Todo
    {
        \DB::transaction(function () {

            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
