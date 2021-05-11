<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\WebsiteController;
use App\User;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Modules\Chat\Entities\Status;
use Modules\RolePermission\Entities\Role;
use Modules\Setting\Model\BusinessSetting;
use Session;
use Spatie\Valuestore\Valuestore;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('general_settings', function() {
            return Valuestore::make((base_path().'/general_settings.json'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->commands([
            InstallCommand::class,
            ClientCommand::class,
            KeysCommand::class,
        ]);

        if (Schema::hasTable('chat_notifications')){
            view()->composer([
                'backend.partials.menu',
                'frontend.infixlmstheme.layouts.dashboard_menu'
            ], function ($view) {
                $notifications = DB::table('chat_notifications')->where('notifiable_id', auth()->id())
                    ->where('read_at', null)
                    ->get();

                foreach ($notifications as $notification){
                    $notification->data = json_decode($notification->data);
                }
                $notifications = $notifications->sortByDesc('created_at');

                $view->with(['notifications_for_chat' => $notifications]);
            });
        }

        if (str_contains(request()->path(), 'chat')){
            view()->composer([
                'frontend.infixlmstheme.layouts.footer',
                'frontend.infixlmstheme.layouts.dashboard_menu',
            ], function ($view) {
                $ob = new WebsiteController();
                $view->with($ob->common());
            });
        }



        view()->composer('*', function ($view)
        {

            $seed = session()->get('user_status_seedable');
            if (moduleStatusCheck('Chat') && auth()->check() && is_null($seed)){
                $users = User::all();
                foreach ($users as $user){
                    Status::create([
                        'user_id' => $user->id,
                        'status' => 0
                    ]);
                }

                session()->put('user_status_seedable', 'false');
            }
        });

        view()->composer('*', function ($view)
        {
            if (auth()->check()){
                $this->app->singleton('extend_view', function($app) {
                    if (auth()->user()->role_id == 3){
                        return 'frontend.infixlmstheme.layouts.dashboard_master';
                    }else{
                        return 'backend.master';
                    }
                });
            }
        });
    }
}
