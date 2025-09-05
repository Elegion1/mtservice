<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for specific routes or conditions if needed
        if ($this->shouldTrack($request)) {
            try {
                // Skip if it's a bot/crawler
                if ($this->isCrawler($request->userAgent())) {
                    return $next($request);
                }

                // Use database transaction to prevent duplicate entries
                DB::transaction(function () use ($request) {
                    // Store only the path to save space and avoid duplicate entries
                    $url = parse_url($request->fullUrl(), PHP_URL_PATH) ?: '/';
                    
                    Visit::firstOrCreate(
                        [
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'url' => $url
                        ],
                        [
                            'visited_at' => now()
                        ]
                    );
                });
            } catch (\Exception $e) {
                // Log the error but don't break the application
                Log::error('Error tracking visit: ' . $e->getMessage());
            }
        }

        return $next($request);
    }

    /**
     * Check if the request should be tracked.
     */
    /**
     * Check if the user agent is a bot/crawler.
     */
    protected function isCrawler($userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $crawlers = [
            'bot', 'spider', 'crawl', 'slurp', 'search', 'yahoo', 'google', 'bing',
            'msn', 'teoma', 'ask', 'yandex', 'baidu', 'facebook', 'twitter',
            'linkedin', 'pinterest', 'whatsapp', 'telegram', 'discord', 'slack',
            'skype', 'viber', 'headless', 'phantom', 'selenium', 'puppeteer', 
            'playwright', 'curl', 'wget', 'python', 'java', 'php', 'ruby', 'perl'
        ];

        foreach ($crawlers as $crawler) {
            if (stripos($userAgent, $crawler) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the request should be tracked.
     */
    protected function shouldTrack(Request $request): bool
    {
        // Skip if not a GET request
        if (!$request->isMethod('get')) {
            return false;
        }

        // Skip if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return false;
        }

        // Skip if it's a request for a file
        $path = $request->path();
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot|map|json|xml)$/i', $path)) {
            return false;
        }
        // Skip tracking for certain routes or conditions
        $excludedRoutes = [
            'horizon*',
            'telescope*',
            'dashboard*',
            'admin*',
            'api*',
            '*.css',
            '*.js',
            '*.jpg',
            '*.jpeg',
            '*.png',
            '*.gif',
            '*.ico',
            '*.svg',
            '*.woff',
            '*.woff2',
            '*.ttf',
            '*.eot',
            '*.map',
            '*.json',
            '*.xml',
            '*.js',
            '*.css',
            '*.jpg',
            '*.jpeg',
            '*.png',
            '*.gif',
            '*.ico',
        ];

        foreach ($excludedRoutes as $route) {
            if ($request->is($route)) {
                return false;
            }
        }

        return true;
    }
}
