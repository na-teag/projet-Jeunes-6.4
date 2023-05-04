<?php
$users = array (
	'test_test' => array (
		'name' => 'TEST',
		'firstname' => 'Test',
		'birth' => '01/01/2000',
		'email' => 'test@test.test',
		'username' => 'test_test',
		'password' => '$2y$10$Q7jgQAwtzpYc1OdYM4dyyeaeggXSCBKqWZrtq9VylYTQBQYhbmgq2',
		'role' => 'jeune',
		'skills' => array(
			array(
				'referent' => array(
					'name'=> 'referent name',
					'firstname'=> 'referent firstname',
					'email' => 'referent@otacos.com',
					'situation' => 'OTacos worker', # retraité salarié femme au foyer etc.
				),
				'beginning' => '2000-01-01',
				'duration' => '1 mois',
				'environement' => 'Otacos company', # association, club, entreprise
				'description' => 'fabrication de tacos', #description de la tâche réalisé
				'socialSkills' => array('skill1', 'skill2', 'skill3'), # 4 skills maximum
				'savoir-faire' => array('skill1', 'skill2', 'skill3'), # 4 skills maximum
                'status' => 'confirmed' # pourrait être toConfirm ou bien archived
			),
		)	
	),
);
?> 

