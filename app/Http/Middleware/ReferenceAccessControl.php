<?php

namespace App\Http\Middleware;

use App\Models\Candidate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ReferenceAccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->user()->id);


        // Determine action based on route name or parameters
        $route = $request->route()->getName();

        switch ($route) {
            case 'platform.references.candidate.create':
                $candidate = Candidate::find($request->route('candidate'));
                if ($candidate
                    && ($request->user()->id !== $candidate->user_id)
                    && !Auth::user()->hasAccess('platform.systems.users')) {
                        abort(403, 'Unauthorized action.');
                }
                break;
            // Add more cases as needed
        }

        // If the user does not have permission, you can abort the request
        // abort(403, 'Unauthorized action.');

        return $next($request);
    }
}
