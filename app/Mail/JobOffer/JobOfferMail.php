<?php

namespace App\Mail\JobOffer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    // يفضل استخدام الـ public properties ليتم تمريرها تلقائياً للـ View
    public $salary;
    public $jobName;
    public $jobDescription;
    public $benefits;

    /**
     * نمرر البيانات عبر الـ Constructor عند إرسال الإيميل
     */
    public function __construct($salary, $jobName, $jobDescription, $benefits)
    {
        $this->salary = $salary;
        $this->jobName = $jobName;
        $this->jobDescription = $jobDescription;
        $this->benefits = $benefits;
    }

    /**
     * إعدادات ظرف الرسالة (العنوان)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'عرض عمل وظيفي - ' . $this->jobName,
        );
    }

    /**
     * ربط كلاس الإيميل بملف الـ Blade
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job_offer_contract', // تأكد أن المسار يطابق مكان ملف الـ Blade
            with: [
                'salary' => $this->salary,
                'job_name' => $this->jobName,
                'job_description' => $this->jobDescription,
                'benefits' => $this->benefits,
            ],
        );
    }

    /**
     * إذا كنت تريد إرسال العقد كـ PDF ملحق (اختياري)
     */
    public function attachments(): array
    {
        return [];
    }
}