<?php

namespace App\Http\Middleware;

use Closure;

class NormalizeUrlPath
{
    /**
     * Redirect malformed paths (double slashes) to canonical single-slash URLs.
     */
    public function handle($request, Closure $next)
    {
        $pathInfo = $request->getPathInfo();

        if (preg_match('#//+#', $pathInfo)) {
            $normalizedPath = preg_replace('#/+#', '/', $pathInfo);
            $queryString = $request->getQueryString();

            $targetUrl = $request->getSchemeAndHttpHost() . $normalizedPath;
            if (!empty($queryString)) {
                $targetUrl .= '?' . $queryString;
            }

            return redirect($targetUrl, 301);
        }

        return $next($request);
    }
}
