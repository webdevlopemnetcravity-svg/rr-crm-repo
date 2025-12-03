<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadVisaType;
use App\Models\NewLeadSubclass;
use Illuminate\Http\Request;

class VisaTypeSettingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.visaTypeSettings';
        $this->activeSettingMenu = 'visa_type_settings';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->visaTypes = NewLeadVisaType::all();
        $this->subclasses = NewLeadSubclass::with('visaType')->get();

        $this->view = 'visa-type-settings.ajax.visa-type';

        $tab = request('tab');

        switch ($tab) {
        case 'subclass':
            $this->pageTitle = 'app.menu.subclass';
            $this->view = 'visa-type-settings.ajax.subclass';
            break;
        default:
            $this->pageTitle = 'app.menu.visaType';
            $this->view = 'visa-type-settings.ajax.visa-type';
            break;
        }

        $this->activeTab = $tab ?: 'visa-type';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('visa-type-settings.index', $this->data);
    }
}

