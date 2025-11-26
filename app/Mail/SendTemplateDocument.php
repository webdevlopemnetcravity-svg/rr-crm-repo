<?php

namespace App\Mail;

use App\Models\NewLead;
use App\Models\NewLeadTemplateDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendTemplateDocument extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $document;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NewLead $lead, NewLeadTemplateDocument $document)
    {
        $this->lead = $lead;
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Template Document: ' . $this->document->name)
            ->view('emails.template-document');

        // Attach the file if it exists
        if ($this->document->file_path) {
            try {
                $disk = Storage::disk(config('filesystems.default'));
                $storagePath = NewLeadTemplateDocument::FILE_PATH . '/' . $this->document->file_path;
                
                // Check if file exists in storage (local or S3)
                if ($disk->exists($storagePath)) {
                    // For local storage, use attach with file path
                    if (config('filesystems.default') == 'local') {
                        $filePath = public_path('user-uploads/' . $storagePath);
                        if (file_exists($filePath)) {
                            $mail->attach($filePath, [
                                'as' => $this->document->file_name ?? $this->document->name,
                                'mime' => $this->document->file_type ?? 'application/octet-stream',
                            ]);
                        }
                    } else {
                        // For S3 or cloud storage, use attachData with file content
                        $fileContent = $disk->get($storagePath);
                        $mail->attachData($fileContent, $this->document->file_name ?? $this->document->name, [
                            'mime' => $this->document->file_type ?? 'application/octet-stream',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to attach file to email: ' . $e->getMessage());
            }
        }

        return $mail;
    }
}

