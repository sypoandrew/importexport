<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Export;
use Aero\Catalog\Models\Product;


class ExportAllProducts extends Export
{
	public function __construct(){
		parent::__construct();
		$timestamp = date("y-m-d-H-i");
		$this->filename = 'all_products_export-'.$timestamp.'.csv';
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
				$featured = '';
				if($t = $product->tags()->where('tag_group_id', $this->tag_groups['Featured']->id)->first()){
					$featured = $t->value;
				}
				
				$publication = $product->additional('publication');
				$drinking_dates = $product->additional('drinking_dates');
				$classification = $product->additional('classification');
				
				$price = 0;
				$p = $variant->prices()->where('quantity', 1)->first();
				if($p != null){
					$price = $p->value;
				}
				
				$line = [
				'sku' => $variant->sku,
				'qty' => $variant->stock_level,
				'price' => $price,
				'name' => $product->name,
				'description' => $product->description,
				'short_description' => $product->summary,
				'bottle_size' => $bottle_size,
				'vintage' => $vintage,
				'pack_size' => $case_size,
				'duty_status' => substr($variant->sku, -2),
				'country_of_produce' => $country,
				'region' => $region,
				'subregion' => $subregion,
				'critics_score' => $critic_score,
				'publication' => $publication,
				'color' => $colour,
				'featured' => $featured,
				'producer' => ($product->manufacturer()->first()) ? $product->manufacturer()->first()->name : '',
				'drinking_dates' => $drinking_dates,
				'classification' => $classification,
				'cru' => $cru,
				];
				
				/* 
				store	websites	attribute_set	type	visibility	tax_class_id	status	sku	qty	price	special_price	special_from_date	special_to_date	name	description	short_description	bottle_size	vintage	pack_size	duty_status	country_of_produce	region	subregion	critics_score	publication	color	featured	supplier	producer	is_in_stock	manage_stock	weight	category_ids	drinking_dates	classification	cru

				 */
				
				
				
				
				
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
