<?php
/*
Simple form build.
*/
return array(
	'content' => array(
		'elements' => array(
			'user' => array( //active model name
				'type'=>'form',
				'title'=>'Login Information',
				'description' => 'Some Description goes here...',
				'items' => array(
					array(
						'row' => array(
							'htmlOptions'=>array('class'=>'rowcls'), /*row tag attibutes declaration, if need*/
							'columns'=> array(
								'username' => array(
									'type' => 'text',
									'maxlength' => 32,
									'hint' => 'enter your username',
								), //first column declaration the form attibute name
								'password' => array(
									'type' => 'password',
									'maxlength' => 32,
									'hint' => 'enter your correct password'
								), //2nd column declation the form attibute name.
							),  
						),
					), //first row declaration
				)
			),
		),
		'buttons' => array(
			'login'=>array(
           		'type'=>'submit',
           		'label'=>'Login',
       		),
		)
	)
);