<?php
/**
 * Web Application Office Model
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Models;
use Components\Helpers\Hash;

class Office extends \Core\Models\DbModel
{
	use \Core\Models\Traits\Belong, \Core\Models\Traits\Linked;
	
	protected 
		$system = [
			'id',
			'linked', 
			'created_at',
			'modified_at'
		],
		$writable = [
			'company_id',
			'address'
		];
	
    /**
     * Get Office Company
	 * @return Company
     */
	public function company()
	{
		return $this->belongsTo(Company::class);
	}
	
    /**
     * Get Office Companys
	 * @return Collection
     */
	public function companys()
	{
		return $this->manyFromMany(Company::class, ['is_main']);
	}
}
