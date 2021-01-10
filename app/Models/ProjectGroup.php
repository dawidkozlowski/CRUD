<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectGroup
 * 
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property float $budget
 *
 * @package App\Models
 */
class ProjectGroup extends Model
{
	protected $table = 'project_groups';
	public $timestamps = false;

	protected $casts = [
		'project_id' => 'int',
		'budget' => 'float'
	];

	protected $fillable = [
		'project_id',
		'name',
		'budget'
	];

    public function projectGroupCampaign()
    {
        return $this->belongsTo(ProjectGroupCampaign::class, 'id', 'project_group_id');
	}
}
