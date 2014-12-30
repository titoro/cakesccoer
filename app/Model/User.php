<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth', 'AppModel', 'Model');

class User extends AppModel{

	public function beforeSave($options = array()){
		if(!$this->id){
			$passwordHasher = new SimplePasswordHasher();
			$this->data['User']['password'] = $passwordHaser->hash($this->data['User']['password']);
		}
		return true;
	}
}
?>
