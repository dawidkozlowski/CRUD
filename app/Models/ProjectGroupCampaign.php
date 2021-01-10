<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectGroupCampaign
 * 
 * @property int $id
 * @property int $project_group_id
 * @property string $name
 * @property int $status
 * @property Carbon $date_start
 *
 * @package App\Models
 */
class ProjectGroupCampaign extends Model
{
	protected $table = 'project_group_campaigns';
	public $timestamps = false;

	protected $casts = [
		'project_group_id' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'date_start'
	];

	protected $fillable = [
		'project_group_id',
		'name',
		'status',
		'date_start'
	];

    public function projectGroup()
    {
        return $this->hasOne(ProjectGroup::class, 'project_group_id', 'id');
	}
}
