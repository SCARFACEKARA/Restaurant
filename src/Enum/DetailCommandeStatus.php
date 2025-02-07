<?php
namespace App\Enum;

enum DetailCommandeStatus: string
{
    case EN_COURS = 'en cours';
    case FINI = 'fini';
    case RECUPERER = 'recuperer';
}
