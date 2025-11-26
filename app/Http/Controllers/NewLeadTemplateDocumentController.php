<?php

namespace App\Http\Controllers;

use App\Helper\Files;
use App\Helper\Reply;
use App\Models\NewLeadTemplateDocument;
use Illuminate\Http\Request;

class NewLeadTemplateDocumentController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newLeadTemplateDocuments';
        $this->activeSettingMenu = 'new_lead_template_documents';
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
        $this->documents = NewLeadTemplateDocument::all();

        return view('new-lead-template-documents.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-lead-template-documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240'
        ]);

        $document = new NewLeadTemplateDocument();
        $document->company_id = company()->id;
        $document->name = $request->name;
        $document->added_by = user()->id;

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = Files::uploadLocalOrS3($file, NewLeadTemplateDocument::FILE_PATH);
            
            $document->file_path = $filename;
            $document->file_name = $file->getClientOriginalName();
            $document->file_size = $file->getSize();
            $document->file_type = $file->getClientMimeType();
        }

        $document->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = NewLeadTemplateDocument::findOrFail($id);
        
        // Delete file from storage
        if ($document->file_path) {
            Files::deleteFile($document->file_path, NewLeadTemplateDocument::FILE_PATH);
        }

        $document->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}

