<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewLeadMainDocument;
use App\Models\NewLeadDependsDocument;
use Illuminate\Http\Request;

class LeadDocumentSettingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leadDocumentSettings';
        $this->activeSettingMenu = 'lead_document_settings';
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
        $this->mainDocuments = NewLeadMainDocument::all();
        $this->dependsDocuments = NewLeadDependsDocument::all();

        $this->view = 'lead-document-settings.ajax.main-documents';

        $tab = request('tab');

        switch ($tab) {
        case 'depends-documents':
            $this->pageTitle = 'app.menu.dependsDocuments';
            $this->view = 'lead-document-settings.ajax.depends-documents';
            break;
        default:
            $this->pageTitle = 'app.menu.mainDocuments';
            $this->view = 'lead-document-settings.ajax.main-documents';
            break;
        }

        $this->activeTab = $tab ?: 'main-documents';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('lead-document-settings.index', $this->data);
    }
}
