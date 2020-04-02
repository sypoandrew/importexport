<?php

namespace Sypo\ImportExport\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Sypo\ImportExport\Models\Export;
use Aero\Catalog\Models\Product;
use Carbon\Carbon;


class ExportAllProducts extends Export
{
	protected $code = 'all_products_export';
	
	public function __construct(){
		parent::__construct();
		$timestamp = Carbon::now()->format('y-m-d-H-i');
		$this->filename = 'all_products_export-'.$timestamp.'.csv';
		$this->contents = $this->get_contents();
		$this->save();
	}
	
	public function get_contents(){
		$lines = [];
		
		$products = Product::get();
		foreach($products as $product){
			
			
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
			
			$publication = $product->additional('publication');
			$drinking_dates = $product->additional('drinking_dates');
			$classification = $product->additional('classification');
			
			$producer = '';
			if($product->manufacturer_id){
				$producer = $product->manufacturer()->first()->name;
			}
			
			$variants = $product->variants()->get();
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
				'name' => $product->name,
				'description' => $this->clean_description($product->description),
				'short_description' => $this->clean_description($product->summary),
				'bottle_size' => $bottle_size,
				'vintage' => $vintage,
				'pack_size' => $case_size,
				'duty_status' => substr($variant->sku, -2),
				'country_of_produce' => $country,
				'producer' => $producer,
				'region' => $region,
				'critics_score' => $critic_score,
				'publication' => $publication,
				'color' => $colour,
				'featured' => $featured,
				'drinking_dates' => $drinking_dates,
				'classification' => $classification,
				'cru' => $cru,
				'supplier' => $supplier,
				'subregion' => $subregion,
				];
				
				#current Magento export
				/* 
				store	websites	attribute_set	type	category_ids	sku	has_options	name	meta_title	meta_description	image	small_image	thumbnail	url_key	url_path	custom_design	page_layout	options_container	image_label	small_image_label	thumbnail_label	msrp_enabled	msrp_display_actual_price_type	gift_message_available	critics_score	designation	classification	publication	drinking_dates	livex_contractid	livex_lastchangeon	price	special_price	weight	msrp	color	status	is_recurring	visibility	tax_class_id	producer	region	vintage	bottle_size	pack_size	country_of_produce	duty_status	wine_type	featured	ebizmarts_mark_visited	cru	description	short_description	meta_keyword	custom_layout_update	tasting_notes	special_from_date	special_to_date	news_from_date	news_to_date	custom_design_from	custom_design_to	qty	min_qty	use_config_min_qty	is_qty_decimal	backorders	use_config_backorders	min_sale_qty	use_config_min_sale_qty	max_sale_qty	use_config_max_sale_qty	is_in_stock	low_stock_date	notify_stock_qty	use_config_notify_stock_qty	manage_stock	use_config_manage_stock	stock_status_changed_auto	use_config_qty_increments	qty_increments	use_config_enable_qty_inc	enable_qty_increments	is_decimal_divided	stock_status_changed_automatically	use_config_enable_qty_increments	product_name	store_id	product_type_id	product_status_changed	product_changed_websites	supplier	subregion	website	_media_image	_media_lable	_media_position	_media_is_disabled
				 */
				
				
				
				
				
				$lines[] = $line;
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
