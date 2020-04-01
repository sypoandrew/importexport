<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Import
{
	protected $file;
	protected $filename;
	protected $contents;
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
	}
	
    /**
     * Save the import file to the server
     *
     * @return void
     */
	public function save(){
		
		Storage::put($this->path.$this->filename, $this->contents);
	}
	
    /**
     * Save the import file to the server
     *
     * @return void
     */
	public function import(File $file){
		
		if(setting('ImportExport.enabled')){
			
			#check file in correct format
			
			
			#save local copy of file
			$this->save();
			
			#handle import routine
			
		}
	}
}
