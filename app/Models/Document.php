<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Document extends Model {

    protected $table = 'document';
    public $timestamps = false;
    protected $primaryKey = 'id';

    //public $timestamps = false; // Disable default timestamps handling

    protected $fillable = [
        //'id',
        'subject',
        'body',
        'sender_id',
        'receiver_id',
        'mailing_date_time',
        'follow_up_id',
        'status_id',
        'pdf',
        'registration_number',
        'importance_id',
    ];

    protected $casts = [
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'follow_up_id' => 'integer',
        'status_id' => 'integer',
        'importance_id'=> 'integer',
        'mailing_date_time' => 'datetime',
        'registration_number' => 'integer',
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

    public function follow_ups(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'doc_id');
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
