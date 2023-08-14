<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class followUp extends Model {

    protected $table = 'follow_ups';
    public $timestamps = false;
    protected $primaryKey = 'id';

    //public $timestamps = false; // Disable default timestamps handling

    // protected $fillable = [
    //     //'id',
    //     'subject',
    //     'body',
    //     'sender_id',
    //     'receiver_id',
    //     'mailing_date_time',
    //     'status_id',
    //     'pdf',
    //     'registration_number',
    //     'importance_id',
    //     'doc_id',
    // ];

    protected $fillable = [
        'subject',
        'body',
        'sender_id',
        'receiver_id',
        'mailing_date_time',
        'status_id',
        'pdf',
        'registration_number',
        'importance_id',
        'doc_id',
        'update_frequency',
        'next_update_date',
    ];

    protected $casts = [
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'follow_up_id' => 'integer',
        'status_id' => 'integer',
        'importance_id'=> 'integer',
        'mailing_date_time' => 'datetime',
        'doc_id' => 'integer',
    ];






    public function setPdfAttribute($value)
    {
        if (is_array($value)) {
            $pdfPaths = [];
            foreach ($value as $file) {
                if ($file instanceof UploadedFile) {
                    $pdfPaths[] = $file->store('pdfs');
                }
            }
            $this->attributes['pdf'] = json_encode($pdfPaths);
        } elseif ($value instanceof UploadedFile) {
            $this->attributes['pdf'] = json_encode([$value->store('pdfs')]);
        }
    }



    public function importance(): BelongsTo
    {
        return $this->belongsTo(Importance::class, 'importance_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'id');
    }







    // public function sender()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    // public function receiver()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }

    // public function followUp()
    // {
    //     return $this->belongsTo(FollowUp::class, 'follow_up_id');
    // }

    // public function status()
    // {
    //     return $this->belongsTo(Status::class, 'status_id');
    // }


    }
