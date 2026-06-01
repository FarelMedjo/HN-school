<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Parametre extends Model
{
    protected $primaryKey = 'idParametre';
    protected $guarded = [];
    protected $table = 'parametres';

    /** Récupère la valeur brute d'un paramètre. */
    public static function get(string $cle, $default = null)
    {
        return static::where('cle', $cle)->value('valeur') ?? $default;
    }

    /** Définit (ou crée) la valeur d'un paramètre. */
    public static function set(string $cle, $valeur): void
    {
        static::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
    }

    /**
     * URL publique d'un paramètre stockant un chemin de fichier (disque "public").
     * Renvoie null si le paramètre est vide ou le fichier absent.
     */
    public static function fichierUrl(string $cle): ?string
    {
        $path = static::get($cle);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
