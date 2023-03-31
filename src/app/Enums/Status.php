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
            Status::PENDING => 'Traitement en cours',
            Status::EXTRACTING_AUDIO => 'Extraction de l\'audio',
            Status::PROCESSING_SUBTITLES => 'Traitement des sous-titres',
            Status::TRANSLATING_SUBTITLES => 'Traduction des sous-titres',
            Status::PROCESSING_CHAPTERS => 'Traitement des chapitres',
            Status::PROCESSING_SUMMARY => 'Traitement du résumé',
            Status::COMPLETE => 'C\'est fini !',
            Status::ERRORED => 'Il y a eu une erreur, vérifiez la console pour plus de détails.',
        };
    }
}