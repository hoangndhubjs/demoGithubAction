<?php
namespace App\Models;

use App\Actions\ActionEvent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model as SystemModel;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;

/**
 * Class Model (Logging actions changes)
 * @package App\Models
 */
class Model extends SystemModel
{
//    protected $dispatchesEvents = [
//        'creating' =>  Create,
//        'updating' => '',
//        'deleting' => ''
//    ]

    protected static function booted() {
        try {
            self::created(function ($model) {
                if (is_array($model->primaryKey)) return true;
                $user = Auth::user();
                ActionEvent::forResourceCreate($user, $model)->save();
            });
            self::updating(function ($model) {
                if (is_array($model->primaryKey)) return true;
                $user = Auth::user();
                ActionEvent::forResourceUpdate($user, $model)->save();
            });
            self::deleted(function ($model) {
                if (is_array($model->primaryKey)) return true;
                $user = Auth::user();
                if (!$model instanceof Collection) {
                    $model = collect([$model]);
                }
                ActionEvent::forResourceDelete($user, $model)->map(function($model) { $model->save();});
            });
            return true;
        } catch (\Exception $e) {
            //Nothing for continuous run app
            return true;
        }
    }
}
