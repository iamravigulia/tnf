<?php
namespace Edgewizz\Tnf\Models;

use Illuminate\Database\Eloquent\Model;

class TnfQues extends Model{
    public function answers(){
        return $this->hasMany('Edgewizz\Tnf\Models\TnfAns', 'question_id');
    }
    protected $table = 'fmt_tnf_ques';
}