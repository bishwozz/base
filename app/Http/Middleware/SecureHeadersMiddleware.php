<?php
namespace App\Http\Middleware;
use Closure;
class SecureHeadersMiddleware
{
    // Enumerate headers which you do not want in your application's responses.
    // Great starting point would be to go check out @Scott_Helme's:
    // https://securityheaders.com/
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        // $response = $next($request);
        // $response->header('X-Frame-Options', 'ALLOW FROM '. env('APP_URL').'/');
        // return $response;
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', $this->getCSP()); // Clearly, you will be more elaborate here.
        return $response;
    }
    private function getCSP(){
        // return "frame-ancestors 'self'; default-src 'self' s7.addthis.com; img-src * data: s7.addthis.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com unpkg.com cdn.datatables.net cdn.jsdelivr.net stackpath.bootstrapcdn.com ajax.googleapis.com cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com cdnjs.cloudflare.com  cdn.datatables.net s7.addthis.com z.moatads.com v1.addthisedge.com m.addthis.com graph.facebook.com ajax.googleapis.com; font-src 'self' data: 'unsafe-inline' fonts.gstatic.com cdnjs.cloudflare.com stackpath.bootstrapcdn.com unpkg.com; connect-src 'self' cdn.jsdelivr.net; frame-src 'self' s7.addthis.com  docs.google.com"; 
        // return "frame-ancestors 'self'; default-src 'self' s7.addthis.com; img-src * data: s7.addthis.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com unpkg.com cdn.datatables.net cdn.jsdelivr.net stackpath.bootstrapcdn.com ajax.googleapis.com cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com cdnjs.cloudflare.com cdn.datatables.net s7.addthis.com z.moatads.com v1.addthisedge.com m.addthis.com graph.facebook.com ajax.googleapis.com; font-src 'self' data: 'unsafe-inline' fonts.gstatic.com cdnjs.cloudflare.com stackpath.bootstrapcdn.com unpkg.com; connect-src 'self' cdn.jsdelivr.net wss://ws-ap2.pusher.com; frame-src 'self' s7.addthis.com docs.google.com";
        return "frame-ancestors 'self'; default-src 'self' s7.addthis.com; img-src * data: s7.addthis.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com unpkg.com cdn.datatables.net cdn.jsdelivr.net stackpath.bootstrapcdn.com ajax.googleapis.com cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com cdnjs.cloudflare.com cdn.datatables.net s7.addthis.com z.moatads.com v1.addthisedge.com m.addthis.com graph.facebook.com ajax.googleapis.com https://code.jquery.com; font-src 'self' data: 'unsafe-inline' fonts.gstatic.com cdnjs.cloudflare.com stackpath.bootstrapcdn.com unpkg.com; connect-src 'self' cdn.jsdelivr.net wss://ws-ap2.pusher.com; frame-src 'self' s7.addthis.com docs.google.com";


        // $csp_arr = config('secure-headers.csp');
        // $arr = array();
        // foreach ($csp_arr as $key => $value) {
        //     if(in_array('self', $value)){}
        //     $arr[] = $key." ".implode(" ", $value);
        // }
        // dd($csp_arr, $arr, );
        // return "default-src 'self'; img-src * unsafe-line data: ; style-src-elm 'self' unsafe-inline https://fonts.googleapis.com https://unpgk.com https://cdn.datatables.net https://cdn.jdselivr.net https://stackpath.bootstrapcdn.com ; script-src 'self' unsafe-inline unsafe-eval https://cnd.jsdelivr.net unpkg.com ;  font-src 'self' data: unsafe-inline  https://fonts.gstatic.com https://stackpath.bootstrapcdn.com https://unpkg.com";
        // return implode(" ; ", $arr);
    }
    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}