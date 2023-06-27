<?php
/**
 * Web Application Company Model
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Models;

class Company extends \Core\Models\DbModel
{
	use \Core\Models\Traits\Has, \Core\Models\Traits\Linked;
	
	protected 
		$system = [
			'id',
			'linked', 
			'created_at',
			'modified_at'
		],
		$writable = [
			'name'
		];
	
    /**
     * Get Company Office
	 * @return Office|null
     */
	public function office()
	{
		return $this->hasOne(Office::class);
	}
	
    /**
     * Get Company Offices
	 * @return Office|null
     */
	public function offices()
	{
		return $this->manyToMany(Office::class, ['is_main']);
	}
	
    /**
     * Get Company Users
	 * @return Collection
     */
	public function users()
	{
		return $this->hasLinked(User::class);
	}
}
