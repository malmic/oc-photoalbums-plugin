<?php

namespace Flosch\Slideshow\Updates;

use DB;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration
{
    public function up()
    { 
        Schema::table('graker_photoalbums_albums', function (Blueprint $table) {
            $table->integer('site_id')->nullable()->index();
            $table->integer('site_root_id')->nullable()->index();
        });
                
        // migrate slideshows
        $albums = DB::connection('octobercmsv1')->select('select * from graker_photoalbums_albums');
        
        foreach($albums as $album) {
            $migratedAlbum = DB::connection('mysql')->table('graker_photoalbums_albums')->insert([
                'id' => $album->id,
                'user_id' => $album->user_id,
                'title' => $album->title,
                'slug' => $album->slug,
                'description' => $album->description,
                'created_at' => $album->created_at,
                'updated_at' => $album->updated_at,
                'front_id' => $album->front_id,
                'site_id' => $album->region_id,
            ]);
        }
        
        // migrate slides
        $photos = DB::connection('octobercmsv1')->select('select * from graker_photoalbums_photos');
        
        foreach($photos as $photo) {
            $migratedPhoto = DB::connection('mysql')->table('graker_photoalbums_photos')->insert(get_object_vars($photo));
        }
    }
    
    public function down()
    {        
        Schema::table('graker_photoalbums_albums', function (Blueprint $table) {
            $table->dropColumn(['site_id', 'site_root_id']);
        });
    }
};