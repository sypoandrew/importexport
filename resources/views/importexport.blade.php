@extends('admin::layouts.main')

@section('content')
    <div class="flex pb-2 mb-4">
        <h2 class="flex-1 m-0 p-0">
		<a href="{{ route('admin.modules') }}" class="btn mr-4">&#171; Back</a>
		<span class="flex-1">Import-Export Routines</span>
		</h2>
    </div>
    @include('admin::partials.alerts')
	<form action="{{ route('admin.modules.importexport') }}" method="post" class="flex flex-wrap mb-8">
		@csrf
		<div class="card mt-4 w-full">
			<h3>Import-Export settings</h3>
			<div class="mt-4 w-full">
			<label for="enabled" class="block">
			<label class="checkbox">
			<input type="checkbox" id="enabled" name="enabled" value="1">
			<span></span>
			</label>Enabled
			</label>
			</div>
		</div>
		
		<div class="card mt-4 p-4 w-full flex flex-wrap"><button type="submit" class="btn btn-secondary">Save</button> </div>
	</form>
	
	<h3>Import Routines</h3>
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
	</form>
		
	
	<h3>Export Routines</h3>
	<form action="{{ route('admin.modules.importexport') }}" method="post" class="flex flex-wrap mb-8">
		@csrf
		<div class="card mt-4 w-full">
			<h3>Export all products</h3>
			<div class="mt-4 w-full">
			<label for="export_all_products_file" class="block">Select file:</label>
			<input type="text" id="export_all_products_file" name="export_all_products_file" autocomplete="off" required="required" class="w-full " value="">
			</div>
		</div>
		
		<div class="card mt-4 p-4 w-full flex flex-wrap"><button type="submit" class="btn btn-secondary">Save</button> </div>
	</form>
	
	<form action="{{ route('admin.modules.importexport') }}" method="post" class="flex flex-wrap mb-8">
		@csrf
		<div class="card mt-4 w-full">
			<h3>Export stock &amp; prices</h3>
			<div class="mt-4 w-full">
			<label for="export_all_products_file" class="block">Select file:</label>
			<input type="text" id="export_all_products_file" name="export_all_products_file" autocomplete="off" required="required" class="w-full " value="">
			</div>
		</div>
		
		<div class="card mt-4 p-4 w-full flex flex-wrap"><button type="submit" class="btn btn-secondary">Save</button> </div>
	</form>
	
	<form action="{{ route('admin.modules.importexport') }}" method="post" class="flex flex-wrap mb-8">
		@csrf
		<div class="card mt-4 w-full">
			<h3>Export fine wine</h3>
			<div class="mt-4 w-full">
			<label for="export_fine_wine_file" class="block">Select file:</label>
			<input type="text" id="export_fine_wine_file" name="export_fine_wine_file" autocomplete="off" required="required" class="w-full " value="">
			</div>
		</div>
		
		<div class="card mt-4 p-4 w-full flex flex-wrap"><button type="submit" class="btn btn-secondary">Save</button> </div>
	</form>
		
@endsection
