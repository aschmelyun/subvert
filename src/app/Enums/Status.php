<?php

namespace App\Enums;

enum Status: int
{
    case PENDING = 0;
    case EXTRACTING_AUDIO = 1;
    case PROCESSING_SUBTITLES = 2;
    case TRANSLATING_SUBTITLES = 3;
    case PROCESSING_CHAPTERS = 4;
    case PROCESSING_SUMMARY = 5;
    case COMPLETE = 98;
    case ERRORED = 99;

    public function message(): string
    {
        return match ($this) {
            Status::PENDING => 'Pending processing',
            Status::EXTRACTING_AUDIO => 'Extracting audio',
            Status::PROCESSING_SUBTITLES => 'Processing subtitles',
            Status::TRANSLATING_SUBTITLES => 'Translating subtitles',
            Status::PROCESSING_CHAPTERS => 'Processing chapters',
            Status::PROCESSING_SUMMARY => 'Processing summary',
            Status::COMPLETE => 'All done!',
            Status::ERRORED => 'There was an error, check the console for more details',
        };
    }
}