<?php
/**
 * Web Application Role Model
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Models;

class Role extends \Core\Models\DbModel
{
	use \Core\Models\Traits\Belong;
	
	protected 
		$hidden = [],
		$writable = ['name', 'code'];
	
    /**
     * Get Role Users
	 * @return Collection
     */
	public function users()
	{
		return $this->belongsToMany(User::class);
	}
}
