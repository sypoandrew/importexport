<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class Export
{
	protected $file;
	protected $filename;
	protected $path;
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
		$this->path = storage_path('importexport/export/');
	}
	
    /**
     * Save the export file to the server
     *
     * @return void
     */
	public function save(){
		
		Storage::put($this->path.$this->filename, $contents);
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
}
