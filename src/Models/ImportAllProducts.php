<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Import;

class ImportAllProducts extends Import
{
	public function __construct(){
		
		$input = $this->path.$this->filename;
		$output = $this->path.$this->filename;
		
		#re-work file from Magento format to Aero format
		$contents = \Artisan::call('transform:csv', [
			$input, $output
		]);
		
		
		$contents = \Artisan::call('aero:import:products:csv '.$output);
	}
}
