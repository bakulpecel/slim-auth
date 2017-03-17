<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class User extends Model
{
	public $db;
	protected $table = 'users';

	protected $fillable = [

	'email',
	'name',
	'password',

	];

	public function setPassword($password)
	{
		$this->update([

			'password' => password_hash($password, PASSWORD_DEFAULT)

		]);
	}

	public function getAll($id)
	{
		$query = "SELECT FROM * users WHERE id =:id ";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();	
	}
}