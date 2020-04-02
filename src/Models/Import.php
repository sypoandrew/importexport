<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Import
{
	protected $language;
	protected $file;
	protected $filename;
	protected $contents;
	protected $path = 'importexport/import/';
	protected $code = 'import';
	
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        $this->language = config('app.locale');
	}
	
    /**
     * Save the import file to the server
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
     * Save the import file to the server
     *
     * @param $file
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
