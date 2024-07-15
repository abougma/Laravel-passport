<?php

namespace App\Http\Middleware;

use App\Models\OauthClient;
use Closure;
use Exception;
use http\Client;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser as JwtParser;

class GetClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $message = [
            'statut' => 'error',
            'message' => "Une erreur est survenue lors de la détection du client. Contactez Formasup."
        ];

        $returnStatut = 401;
        $jeton = $request->bearerToken();
        $error = false;

        try {
            $clientId = app(JwtParser::class)->parse($jeton)->claims()->get('aud')[0];
            $client = OauthClient::find($clientId);

            if (!$client) {
                $error = true;
            }
        } catch (Exception $e) {
            $error = true;
        }
        if ($error) {
            return response()->json($message, $returnStatut);
        }

        $request->merge([
            'source_name' => $client->name
        ]);

        return $next($request);
    }
}
