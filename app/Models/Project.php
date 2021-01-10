<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * 
 * @property int $id
 * @property bool $active
 * @property string $website
 * @property string $name
 *
 * @package App\Models
 */
class Project extends Model
{
	protected $table = 'projects';
	public $timestamps = false;

	protected $fillable = [
		'active',
		'website',
		'name'
	];

    public function projectGroup()
    {
        return $this->belongsTo(ProjectGroup::class, 'id', 'project_id');
	}

    public function status()
    {
        if ($this->active == 0) {
            return 'INACTIVE';
        } elseif($this->active == 1) {
            return 'ACTIVE';
        } elseif($this->active == 2) {
            return 'IN PROGRESS';
        } else {
            return 'NO STATUS';
        }
	}
}
