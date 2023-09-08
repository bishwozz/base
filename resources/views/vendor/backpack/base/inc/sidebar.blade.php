@php
    use App\Models\MinistryMember;
    use Illuminate\Support\Facades\DB;

    if (isset(backpack_user()->mp_id)) {
        $mp_id = backpack_user()->mp_id;
        $ministry = DB::table('ec_ministry_members as eccm')
            ->select('ecc.name_en', 'ecc.name_lc')
            ->leftJoin('ec_ministry as ecc', 'ecc.id', '=', 'eccm.ministry_id')
            ->where('eccm.mp_id', $mp_id)
            ->first();
        if (lang() == 'lc') {
            $ministry_name = $ministry->name_lc??'';
        } else {
            $ministry_name = $ministry->name_en??'';
        }
    }

    $ministry_name = backpack_user()->name;
    if (isset(backpack_user()->ministry_id)) {
        $ministry = backpack_user()->ministry;
        if (lang() == 'lc') {
            $ministry_name = $ministry->name_lc??'';
        } else {
            $ministry_name = $ministry->name_en??'';
        }
    }
    //   dd(backpack_user());
@endphp

@if (backpack_auth()->check())
    <!-- Left side column. contains the sidebar -->
    <div class="sidebar sidebar-pills">
        <!-- sidebar: style can be found in sidebar.less -->
        <div class="user-panel mt-2">
            <h4 class="text-left" style="color:white; font-weight:bold; font-size:17px; "
                title="{{ backpack_user()->name }}">{{ $ministry_name }}</h4>
            <h6 class="text-center">{{ backpack_user()->email }}</h6>
            @if (isset(backpack_user()->mp_id) && isset($ministry))
                <center>
                    <h4 style="color:white; font-weight:bold; font-size:12px;" title="{{ backpack_user()->name }}">
                        {{ backpack_user()->name }}</h4>
                </center>
            @endif
            <center>
                <h5 style="color: white; font-size:12px; font-weight:bold">
                    {{ Session::get('last_login_user') ? 'Last Login: ' . Carbon\Carbon::parse(Session::get('last_login_user'))->diffForHumans() : '' }}
                </h5>
            </center>
        </div>

        <nav class="sidebar-nav overflow-hidden">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="nav">
                <!-- <li class="nav-title">{{ trans('backpack::base.administration') }}</li> -->
                <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->

                @include(backpack_view('inc.sidebar_content'))

                <!-- ======================================= -->
                <!-- <li class="divider"></li> -->
                <!-- <li class="nav-title">Entries</li> -->
            </ul>
        </nav>
        <!-- /.sidebar -->
    </div>
@endif

@push('before_scripts')
    <script type="text/javascript">
        // Save default sidebar class
        let sidebarClass = (document.body.className.match(/sidebar-(sm|md|lg|xl)-show/) || ['sidebar-lg-show'])[0];
        let sidebarTransition = function(value) {
            document.querySelector('.app-body > .sidebar').style.transition = value || '';
        };

        // Recover sidebar state
        let sessionState = sessionStorage.getItem('sidebar-collapsed');

        let mp_id = "{{ backpack_user()->mp_id }}";
        if (mp_id) {
            sessionState = 1;
            document.querySelectorAll('.sidebar-toggler').forEach(function(toggler) {
                $(toggler).css('display', 'none');
            });
        }
        if (sessionState) {
            // disable the transition animation temporarily, so that if you're browsing across
            // pages with the sidebar closed, the sidebar does not flicker into the view
            sidebarTransition("none");
            document.body.classList.toggle(sidebarClass, sessionState === '1');

            // re-enable the transition, so that if the user clicks the hamburger menu, it does have a nice transition
            setTimeout(sidebarTransition, 100);
        }
    </script>
@endpush

@push('after_scripts')
    <script>
        // Store sidebar state
        document.querySelectorAll('.sidebar-toggler').forEach(function(toggler) {
            toggler.addEventListener('click', function() {
                sessionStorage.setItem('sidebar-collapsed', Number(!document.body.classList.contains(
                    sidebarClass)))

                let mp_id = "{{ backpack_user()->mp_id }}";
                if (mp_id) {
                    sessionStorage.setItem('sidebar-collapsed', 1);
                }
                // wait for the sidebar animation to end (250ms) and then update the table headers because datatables uses a cached version
                // and dont update this values if there are dom changes after the table is draw. The sidebar toggling makes
                // the table change width, so the headers need to be adjusted accordingly.

                setTimeout(function() {
                    if (typeof crud !== "undefined" && crud.table) {
                        crud.table.fixedHeader.adjust();
                    }
                }, 300);
            })
        });
        // Set active state on menu element
        var full_url = "{{ Request::fullUrl() }}";
        var $navLinks = $(".sidebar-nav li a, .app-header li a");

        // First look for an exact match including the search string
        var $curentPageLink = $navLinks.filter(
            function() {
                return $(this).attr('href') === full_url;
            }
        );

        // If not found, look for the link that starts with the url
        if (!$curentPageLink.length > 0) {
            $curentPageLink = $navLinks.filter(function() {
                if ($(this).attr('href').startsWith(full_url)) {
                    return true;
                }

                if (full_url.startsWith($(this).attr('href'))) {
                    return true;
                }

                return false;
            });
        }

        // for the found links that can be considered current, make sure
        // - the parent item is open
        $curentPageLink.parents('li').addClass('open');
        // - the actual element is active
        $curentPageLink.each(function() {
            $(this).addClass('active');
        });
    </script>
@endpush
