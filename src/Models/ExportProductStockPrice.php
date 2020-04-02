<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Export;
use Aero\Catalog\Models\Product;
use Carbon\Carbon;


class ExportProductStockPrice extends Export
{
	protected $code = 'product_stock_price_export';
	
	public function __construct(){
		parent::__construct();
		$timestamp = Carbon::now()->format('y-m-d-H-i');
		$this->filename = 'product_stock_price_export-'.$timestamp.'.csv';
		$this->contents = $this->get_contents();
		$this->save();
	}
	
	public function get_contents(){
		$lines = [];
		
		$products = Product::where('model', 'like', 'AG%')->where('active', 1)->get();
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
				$featured = '';
				$supplier = '';
				
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
					elseif($tag->tag_group_name == 'Featured'){
						$featured = $tag->value;
					}
					elseif($tag->tag_group_name == 'Supplier'){
						$supplier = $tag->value;
					}
				}
				
				
				
				$producer = '';
				if($product->manufacturer_id){
					$producer = $product->manufacturer()->first()->name;
				}
				
				$publication = $product->additional('publication');
				$drinking_dates = $product->additional('drinking_dates');
				$classification = $product->additional('classification');
				
				$image_src = '';
				$img = $product->images()->first();
				if($img != null){
					if($img->source != ''){
						$image_src = $img->source;
					}
					else{
						$image_src = $img->file;
					}
				}
				
				$url_path = $product->getUrl();
				
				#dd($variants);
				foreach($variants as $variant){
					$price = 0;
					$special_price = '';
					$special_start = '';
					$special_end = '';
					$p = $variant->prices()->where('quantity', 1)->first();
					if($p != null){
						$price = $p->value;
						$special_price = $p->sale_value;
						if($p->start_at){
							$special_start = Carbon::parse($p->start_at)->format('d/m/Y H:i');
						}
						if($p->end_at){
							$special_end =  Carbon::parse($p->end_at)->format('d/m/Y H:i');
						}
					}
					
					$line = [
					'sku' => $variant->sku,
					'qty' => $variant->stock_level,
					'price' => $price,
					'special_price' => $special_price,
					'special_from_date' => $special_start,
					'special_to_date' => $special_end,
					'name' => $product->name,
					'bottle_size' => $bottle_size,
					'vintage' => $vintage,
					'pack_size' => $case_size,
					'duty_status' => substr($variant->sku, -2),
					'country_of_produce' => $country,
					'region' => $region,
					'critics_score' => $critic_score,
					'publication' => $publication,
					'color' => $colour,
					'featured' => $featured,
					'supplier' => $supplier,
					'producer' => $producer,
					'active' => $product->active,
					'image' => $image_src,
					'short_description' => $this->clean_description($product->summary),
					'url_path' => $url_path,
					];
					
					#current Magento export
					/* sku	qty	price	special_price	special_from_date	special_to_date	name	bottle_size	vintage	pack_size	duty_status	country_of_produce	region	critics_score	publication	is_in_stock	color	featured	type	supplier	producer	attribute_set	store	visibility	weight	manage_stock	tax_class_id	status	image	short_description	url_path
				 */
					
					
					
					
					
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
