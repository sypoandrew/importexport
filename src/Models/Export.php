<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Aero\Catalog\Models\TagGroup;
use Sypo\ImportExport\Models\ImportExport;


class Export
{
	protected $language;
	protected $file;
	protected $filename;
	protected $path;
	protected $headers;
	protected $tag_groups;
	protected $code = 'export';
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        $this->language = config('app.locale');
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
			
			$user = \Auth::user();
			$log = new ImportExport;
			if($user){
				$log->user_id = $user->id;
			}
			$log->code = $this->code;
			$log->save();
		}
		catch(RunTimeException $e){
			Log::warning($e);
		}
	}
	
    /**
     * Clean the description for CSV output
     *
     * @param string $desc
     * @return string
     */
	protected function clean_description($desc){
		$desc = str_replace(["\r", "\n", "\t"], '', $desc);
		return $desc;
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
