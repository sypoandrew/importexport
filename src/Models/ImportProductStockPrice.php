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
		
		#test file - assumed this is previously uploaded to server
		$this->filename = 'product_stock_price_export-20-04-02-12-33.csv';
		
		$input = $this->path.$this->filename;
		$output = $this->path.'aero_transformed_'.$this->filename;
		
		#re-work file from Magento format to Aero format
		\Artisan::call('transform:product_stock_price', [
			'path' => $input, 
			'output' => $output
		]);
		
		#$this->contents = File($output);
		
		#test file previously transformed
		/* $output = $this->path.'aero_transformed_product_stock_price_export-20-04-02-12-33.csv';
		\Artisan::call('aero:import:products:csv', [
		#\Artisan::call(\Aero\DataPort\Commands\ImportProductsCSVCommand::class, [
			'path' => $output
		]); */
		dd('done');
	}
	
	public function process(){
	}
}
