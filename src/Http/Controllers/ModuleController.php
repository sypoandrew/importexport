<?php

namespace Sypo\ImportExport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Aero\Admin\Facades\Admin;
use Aero\Admin\Http\Controllers\Controller;
use Sypo\ImportExport\Models\ExportProductStockPrice;
use Sypo\ImportExport\Models\ExportFineWine;
use Sypo\ImportExport\Models\ExportAllProducts;

class ModuleController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Show main settings form
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('importexport::importexport', $this->data);
    }
    
	/**
     * Update settings
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
		$res = ['success'=>false,'data'=>false,'error'=>[]];
		
        return redirect()->back()->with('message', 'Settings updated.');
    }
    
	/**
     * Process and get the "all products" export file
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function get_export_all_products(Request $request)
    {
		$e = new ExportAllProducts;
		return response()->download(storage_path('app/'.$e->get_location()));
    }
    
	/**
     * Process and get the "fine wine" export file
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function get_export_fine_wine(Request $request)
    {
		$e = new ExportFineWine;
		return response()->download(storage_path('app/'.$e->get_location()));
	}
    
	/**
     * Process and get the "product stock price" export file
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function get_export_product_stock_price(Request $request)
    {
		$e = new ExportProductStockPrice;
		return response()->download(storage_path('app/'.$e->get_location()));
	}
}
