<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Membership;
use App\Students;

class WorkSpaces extends Model
{

    protected $table = 'workspaces';

    public function getMember(){
        return Membership::join('students', 'students.id', '=', 'membership.student_id')
                ->join('workspaces', 'workspaces.id', '=', 'membership.workspace_id')
                ->where('workspace_id', $this->id)
                ->get();
    }

}
