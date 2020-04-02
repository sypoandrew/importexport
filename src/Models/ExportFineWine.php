<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Export;
use Aero\Catalog\Models\Product;
use Carbon\Carbon;


class ExportFineWine extends Export
{
	protected $code = 'vinquinn_fine_wine_export';
	
	public function __construct(){
		parent::__construct();
		$timestamp = Carbon::now()->format('y-m-d-H-i');
		$this->filename = 'vinquinn_fine_wine_export-'.$timestamp.'.csv';
		$this->contents = $this->get_contents();
		$this->save();
	}
	
	public function get_contents(){
		$lines = [];
		
		$products = Product::where('active', 1)->get();
		foreach($products as $product){
			
			$variants = $product->variants()->where('stock_level', '>', 0)->get();
			if($variants){
				
				$vintage = '';
				$bottle_size = '';
				$case_size = '';
				$region = '';
				$subregion = '';
				$colour = '';
				$critic_score = '';
				$country = '';
				$cru = '';
				
				
				$tags = $product->tags()->select('tags.name->'.$this->language.' as value', 'tag_groups.name->'.$this->language.' as tag_group_name')->join('tag_groups', 'tag_groups.id', '=', 'tags.tag_group_id')->get();
				foreach($tags as $tag){
					
					if($tag->tag_group_name == 'Vintage'){
						$vintage = $tag->value;
					}
					elseif($tag->tag_group_name == 'Bottle Size'){
						$bottle_size = $tag->value;
					}
					elseif($tag->tag_group_name == 'Case Size'){
						$case_size = $tag->value;
					}
					elseif($tag->tag_group_name == 'Region'){
						$region = $tag->value;
					}
					elseif($tag->tag_group_name == 'Sub Region'){
						$subregion = $tag->value;
					}
					elseif($tag->tag_group_name == 'Colour'){
						$colour = $tag->value;
					}
					elseif($tag->tag_group_name == 'Critic Score'){
						$critic_score = $tag->value;
					}
					elseif($tag->tag_group_name == 'Country'){
						$country = $tag->value;
					}
					elseif($tag->tag_group_name == 'Burgundy Cru'){
						$cru = $tag->value;
					}
				}
				
				
				$producer = '';
				if($product->manufacturer_id){
					$producer = $product->manufacturer()->first()->name;
				}
				
				$publication = $product->additional('publication');
				$drinking_dates = $product->additional('drinking_dates');
				
				foreach($variants as $variant){
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
					'producer' => $producer,
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
					
					#current Magento export
					/* sku	qty	vintage	name	producer	bottle_size	price	pack_size	duty_status	country_of_produce	region	subregion	color	critics_score	publication	drinking_dates	cru */
					
					$lines[] = $line;
				}
			}
		}
		
		#generate and return the file contents
		$content = '';
		$headers_added = false;
		foreach($lines as $row){
			if(!$headers_added){
				$content .= implode(",", array_keys($row)) . "\r\n";
				$headers_added = true;
			}
			$content .= '"' . implode('","', $row) . '"' . "\r\n";
		}
		
		return $content;
	}
}
