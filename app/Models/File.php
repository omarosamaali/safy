<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'name',
        'folder',
        'path',
    ]; 


    public function tracks()
    {
        return $this->belongsToMany(Track::class,'track_file');
    } 


    
    public static function getFolder(){

        $folders = DB::table('files')->select('folder')->groupBy('folder')->get();
        return $folders;

    }
}
