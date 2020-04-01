<?php

namespace Sypo\ImportExport\Providers;

use Aero\Admin\AdminModule;
use Aero\Common\Providers\ModuleServiceProvider;
use Aero\Common\Facades\Settings;
use Aero\Common\Settings\SettingGroup;

class ServiceProvider extends ModuleServiceProvider
{
    protected $commands = [];
    
    public function register(): void 
    {
        AdminModule::create('ImportExport')
            ->title('Import Export')
            ->summary('Custom import and export routines for Aero Commerce')
            ->routes(__DIR__ .'/../../routes/admin.php')
            ->route('admin.modules.importexport');
    }
	
    public function boot(): void 
    {
        Settings::group('ImportExport', function (SettingGroup $group) {
            $group->boolean('enabled')->default(true);
        });
		
		$this->loadViewsFrom(__DIR__ . '/../../resources/views/', 'importexport');
    }
}