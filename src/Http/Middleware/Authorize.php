<?php

namespace Bakerkretzmar\AssetManager\Http\Middleware;

use Bakerkretzmar\AssetManager\AssetManagerTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(AssetManagerTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
