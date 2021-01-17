<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $perm)
    {
        if(!auth()->check() || (auth()->user()->id != 1 && !auth()->user()->hasPermission($perm))) {
            $permission = Auth::user()->permissions->first();

            if($permission === null) return abort(404);

            $address = [
                'statistics' => 'statistics',
                'users' => 'users',
                'mailing' => 'mailing',
                'countries' => 'countries-list',
                'cities' => 'cities-list',
                'rubrics' => 'rubrics-list',
                'subsections' => 'subsections-rubric',
                'ads' => 'ads-moderation',
                'moderators' => 'moderators-list',
                'languages' => 'languages-list',
                'contacts' => 'contacts-general',
                'answers' => 'answers',
                'payment' => 'admin-qiwi',
                'settings' => 'settings-main'
            ];

            if(isset($address[$permission->name])) {
                return redirect(route($address[$permission->name]));
            }
            else {
                abort(404);
            }
        }

        return $next($request);
    }
}
