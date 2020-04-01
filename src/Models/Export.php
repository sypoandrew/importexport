<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Aero\Catalog\Models\TagGroup;


class Export
{
	protected $file;
	protected $filename;
	protected $path;
	protected $headers;
	protected $tag_groups;
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
		$this->get_tag_groups();
		$this->path = 'importexport/export/';
	}
	
    /**
     * Save the export file to the server
     *
     * @return void
     */
	public function save(){
		try{
			Storage::put($this->path.$this->filename, $this->contents);
		}
		catch(RunTimeException $e){
			Log::warning($e);
		}
	}
	
    /**
     * Save the export file to the server
     *
     * @return void
     */
	public function export(){
		
		#generate export...
		
		#save a copy locally
		$this->save();
		
		#send file to user...
	}

    /**
     * Get all required tag groups for importing tag data
     *
     * @return Aero\Catalog\Models\TagGroup
     */
    protected function get_tag_groups()
    {
		if($this->tag_groups){
			return $this->tag_groups;
		}
		$groups = TagGroup::get();
		
		$this->tag_groups = [];
		foreach($groups as $g){
			$this->tag_groups[$g->name] = $g;
		}
		#Log::debug($this->tag_groups);
		return $this->tag_groups;
    }
}
