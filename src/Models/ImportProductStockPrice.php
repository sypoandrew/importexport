<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Import;

class ImportProductStockPrice extends Import
{
	public function __construct(){
		parent::__construct();
		
		$this->filename = 'product_stock_price_export-20-04-02-12-33.csv';
		
		$input = $this->path.$this->filename;
		$output = $this->path.'aero_transformed_'.$this->filename;
		
		#re-work file from Magento format to Aero format
		\Artisan::call('transform:productstockprice', [
			'path' => $input, 
			'output' => $output
		]);
		
		#$this->contents = File($output);
		
		#$contents = \Artisan::call('aero:import:products:csv '.$output);
	}
}
