<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Export;
use Aero\Catalog\Models\Product;


class ExportFineWines extends Export
{
	public function __construct(){
		parent::__construct();
		$timestamp = date("y-m-d-H-i");
		$this->filename = 'vinquinn_fine_wine_export-'.$timestamp.'.csv';
		$this->contents = $this->get_contents();
		$this->save();
	}
	
	public function get_contents(){
		$lines = [];
		
		$products = Product::where('active', 1)->get();
		foreach($products as $product){
			
			$variants = $product->variants()->get();
			#dd($variants);
			foreach($variants as $variant){
				
				$vintage = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Vintage']->id)->first()){
					$vintage = $t->value;
				}
				$bottle_size = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Bottle Size']->id)->first()){
					$bottle_size = $t->value;
				}
				$case_size = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Case Size']->id)->first()){
					$case_size = $t->value;
				}
				$region = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Region']->id)->first()){
					$region = $t->value;
				}
				$subregion = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Sub Region']->id)->first()){
					$subregion = $t->value;
				}
				$colour = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Colour']->id)->first()){
					$colour = $t->value;
				}
				$critic_score = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Critic Score']->id)->first()){
					$critic_score = $t->value;
				}
				$country = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Country']->id)->first()){
					$country = $t->value;
				}
				$cru = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Burgundy Cru']->id)->first()){
					$cru = $t->value;
				}
				
				$publication = $product->additional('publication');
				$drinking_dates = $product->additional('drinking_dates');
				
				$price = 0;
				$p = $variant->prices()->where('quantity', 1)->first();
				if($p != null){
					$price = $p->value;
				}
				
				$line = [
				'sku' => $variant->sku,
				'qty' => $variant->stock_level,
				'vintage' => $vintage,
				'name' => $product->name,
				'producer' => ($product->manufacturer()->first()) ? $product->manufacturer()->first()->name : '',
				'bottle_size' => $bottle_size,
				'price' => $price,
				'pack_size' => $case_size,
				'duty_status' => substr($variant->sku, -2),
				'country_of_produce' => $country,
				'region' => $region,
				'subregion' => $subregion,
				'color' => $colour,
				'critics_score' => $critic_score,
				'publication' => $publication,
				'drinking_dates' => $drinking_dates,
				'cru' => $cru,
				];
				
				
				$lines[] = $line;
			}
		}
		
		#generate and return the file contents
		$content = '';
		$added_headers = false;
		foreach($lines as $row){
			if(!$added_headers){
				$content .= implode(",", array_keys($row));
				$added_headers = true;
			}
			$content .= implode(",", $row);
		}
		
		return $content;
	}
}
