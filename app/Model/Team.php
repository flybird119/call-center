<?php
App::uses('AppModel', 'Model');

class Team extends AppModel {
	var $name="Team";
	
	 var $hasAndBelongsToMany = array(
        'User' => array(
            'className' => 'User',
            'joinTable' => 'team_users',
            'foreignKey' => 'team_id',
            'associationForeignKey' => 'user_id',
            'with' => 'TeamUser',
        ),
    );  
	
		function team_validation() {
		$validate1 = array(
				'name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter team name.')
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
}