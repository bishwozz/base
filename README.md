<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


$agenda_approval_history = DB::table('agenda_approval_history as aah')
            ->select('aah.role_id','aah.status_id','aah.remarks', 'ag.is_submitted')
            ->join('agendas as ag','ag.id','aah.agenda_id')
            ->where('ag.id',$this->id)->latest('aah.updated_at')->first();

 if($agenda_approval_history){

            if($agenda_approval_history->role_id == Config::get('roles.id.chief_secretary')){
                if($agenda_approval_history->status_id == 1){
                    return '<a  class="btn btn-success text-white btn-sm">'.trans("प्रमुख सचिव द्वारा स्वीकृत").'</a>';
                }else{
                    return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("प्रमुख सचिव द्वारा फिर्ता ").'</a>';
                }
            }elseif($agenda_approval_history->role_id == Config::get('roles.id.cabinet_approver')){
                if($agenda_approval_history->status_id == 1){
                    return '<a  class="btn btn-success btn-sm">'.trans(" प्रमुख सचिव लाइ पेश ").'</a>';
                }else{
                    return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("मुख्य मन्त्रि कार्यालय रिभ्युअर द्वारा फिर्ता ").'</a>';
                }
            }elseif($agenda_approval_history->role_id == Config::get('roles.id.cabinet_creator')){
                if($agenda_approval_history->status_id == 1){
                    return '<a  class="btn btn-success btn-sm">'.trans("मुख्य मन्त्रि कार्यालय रिभ्युअर लाइ पेश ").'</a>';
                }else{
                    return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("मुख्य मन्त्रि कार्यालय अपरेटर द्वारा फिर्ता ").'</a>';
                }
            }elseif($agenda_approval_history->role_id == Config::get('roles.id.ministry_secretary')){
                if($agenda_approval_history->status_id == 1){
                    return '<a  class="btn btn-success btn-sm">'.trans("मुख्य मन्त्रि कार्यालय अपरेटर लाइ पेश ").'</a>';
                }else{
                    return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("मन्त्रालय सचिव द्वारा फिर्ता ").'</a>';
                }
            }elseif($agenda_approval_history->role_id == Config::get('roles.id.ministry_reviewer')){
                if($agenda_approval_history->status_id == 1){
                    return '<a  class="btn btn-success btn-sm">'.trans("मन्त्रालय सचिव लाइ पेश ").'</a>';
                }else{
                    return '<a data-fancybox data-type="ajax" data-src="/admin/agenda/'.$this->id.'/decisionDialog" href="javascript:;" class="btn btn-danger btn-sm">'.trans("मन्त्रालय रिभ्युअर द्वारा फिर्ता ").'</a>';
                }
            }else{
                if($agenda_approval_history->is_submitted == true){
                    if(!$user->hasRole(Config::get('roles.name.ministry_reviewer'))){
                        return '<a  class="btn btn-success btn-sm">'.trans("मन्त्रालय रिभ्युअर लाइ पेश ").'</a>';
                    }else{
                        return '<a  class="btn btn-success btn-sm">'.trans("स्वीकृत गर्नुहोस").'</a>';
                    }
                }else{
                    return '<a  class="btn btn-success btn-sm">'.trans("पेश गर्नुहोस").'</a>';
                }
            }
        }
