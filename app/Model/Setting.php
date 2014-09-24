<?php
App::uses('AppModel', 'Model');

class Setting extends AppModel {
	var $name="Setting";
	
	var $validate = array( 
			'email_contact' => array(
				'noEmail' => array(
					'rule'    => 'notEmpty',
					'message' => 'Please enter email.'
				),
				'validEmail' => array(
					'rule'    => 'email',
					'message' => 'Please enter a valid email.'
				)
			),
			'email_referral' => array(
					'noEmail' => array(
							'rule'    => 'notEmpty',
							'message' => 'Please enter email.'
					),
					'validEmail' => array(
							'rule'    => 'email',
							'message' => 'Please enter a valid email.'
					)
			)
		);
}