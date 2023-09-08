<?php
namespace App\Base\Traits;

use ReflectionClass;


/**
 *
 */
trait ParentData
{


    public function parent($name)
    {
        // dd($this->crud);
        $request = $this->request;
        return $request->route($name) ?? null;
    }

    public function setUpLinks($methods = ['index'])
    {
        $currentMethod = $this->crud->getActionMethod();
        $exits = method_exists($this, 'tabLinks');
        if ($exits && in_array($currentMethod, $methods)) {
            $this->data['tab_links'] = $this->tabLinks();
        }
    }

    public function enableDialog( $enable = true)
    {
        $this->data['controller'] = (new \ReflectionClass($this))->getShortName();
        $this->crud->controller = $this->data['controller'];
        $this->enableDialog = $enable;
        $this->data['enableDialog'] = property_exists($this, 'enableDialog') ? $this->enableDialog : false;
        $this->crud->enableDialog = property_exists($this, 'enableDialog') ? $this->enableDialog : false;
    }

    public function setMinistryTabs()
    {
        $parameters = array_values(request()->route()->parameters);
        $links = [];
        $links[] = ['label' => trans('MinistryMember.ministry_detail'), 'href' => backpack_url('/ministry').'/'.$parameters[0].'/edit'];
        $links[] = ['label' => trans('MinistryMember.ministry_member_list'), 'href' => backpack_url('/ministry').'/'.$parameters[0].'/ministrymember'];
        $links[] = ['label' => trans('MinistryMember.ministry_employee_list'), 'href' => backpack_url('/ministry').'/'.$parameters[0].'/ministryemployee'];
        return $links;
    }
    public function setCommitteeTabs()
    {
        $parameters = array_values(request()->route()->parameters);
        $links = [];
        $links[] = ['label' => trans('समिति'), 'href' => backpack_url('/committee').'/'.$parameters[0].'/edit'];
        $links[] = ['label' => trans('समिति सदस्यहरु'), 'href' => backpack_url('/committee').'/'.$parameters[0].'/members'];
        return $links;
    }

    public function setEcMpTabs()
    {
        $parameters = array_values(request()->route()->parameters);

        $links = [];
        $links[] = ['label' => trans('menu.ecMps'), 'href' => backpack_url('ec-mp/'.$parameters[0].'/edit')];
        $links[] = ['label' => trans('menu.ecMptenure'), 'href' => backpack_url('ec-mp/'.$parameters[0].'/tenure')];
        return $links;

    }

    public function setEcMeetingRequestTabs(){
        $parameters = array_values(request()->route()->parameters);

        $links = [];
        $links[] = ['label' => trans('menu.ecMeetingRequest'), 'href' => backpack_url('ec-meeting-request/'.$parameters[0].'/edit')];
        $links[] = ['label' => trans('menu.meetingAttendanceDetails'), 'href' => backpack_url('ec-meeting-request/'.$parameters[0].'/meeting-attendance-detail')];
        return $links;
    }


}
