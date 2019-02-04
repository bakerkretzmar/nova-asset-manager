<?php

namespace Bakerkretzmar\AssetManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Bakerkretzmar\AssetManager\AssetManagerTool;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return app(AssetManagerTool::class)->authorize($request)
        ? $next($request)
        : abort(403);
    }
}
