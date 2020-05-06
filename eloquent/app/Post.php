<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function getGetTitleAttribute(){
        return \ucfirst($this->title);
    }
    public function setTitleAttribute($value){
        $this->attributes['title'] = \strtolower($value);
    }
}
