@extends('admin::layouts.main')

@section('content')
    <div class="flex pb-2 mb-4">
        <h2 class="flex-1 m-0 p-0">
		<a href="{{ route('admin.modules') }}" class="btn mr-4">&#171; Back</a>
		<span class="flex-1">Import-Export Routines</span>
		</h2>
    </div>
    @include('admin::partials.alerts')
	<h2>Import Routines</h2>
		<div class="card mt-4 mb-4 p-4 w-full flex flex-wrap">
			<p>TBC</p>
		</div>
	<!--
	<form action="{{ route('admin.modules.importexport') }}" method="post" class="flex flex-wrap mb-8">
		@csrf
		<div class="card mt-4 w-full">
			<h3>Import all products</h3>
			<div class="mt-4 w-full">
			<label for="import_all_products_file" class="block">Upload file:</label>
			<input type="text" id="import_all_products_file" name="import_all_products_file" autocomplete="off" required="required" class="w-full " value="">
			</div>
		</div>
		
		<div class="card mt-4 p-4 w-full flex flex-wrap"><button type="submit" class="btn btn-secondary">Save</button> </div>
	</form>-->
		
	
	<h2>Export Routines</h2>
	<form action="{{ route('admin.modules.importexport.get_export_all_products') }}" method="get" class="flex flex-wrap mb-8">
		@csrf
		
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<h3 class="w-full">Export all products</h3>
			<p>Full export of all Aero Commerce products. Output in Magento format.</p>
		</div>
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<button type="submit" class="btn btn-secondary">Export</button>
		</div>
	</form>
	
	<form action="{{ route('admin.modules.importexport.get_export_fine_wine') }}" method="get" class="flex flex-wrap mb-8">
		@csrf
		
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<h3 class="w-full">Export fine wine</h3>
			<p>Export of all active in-stock Aero Commerce products. Output in Magento format. Reduced columns suitable for sending to clients.</p>
		</div>
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<button type="submit" class="btn btn-secondary">Export</button>
		</div>
	</form>
	
	<form action="{{ route('admin.modules.importexport.get_export_product_stock_price') }}" method="get" class="flex flex-wrap mb-8">
		@csrf
		
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<h3 class="w-full">Export product, stock &amp; prices</h3>
			<p>Export of all active AG in-stock Aero Commerce products. Output in Magento format. Reduced columns for updating key information only.</p>
		</div>
		<div class="card mt-4 p-4 w-full flex flex-wrap">
			<button type="submit" class="btn btn-secondary">Export</button>
		</div>
	</form>
		
@endsection
