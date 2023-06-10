<?php $users = array (
  'test_test' => 
  array (
    'name' => 'TEST',
    'firstname' => 'Test',
    'gender' => 'other',
    'birth' => '2000-01-01',
    'email' => 'test@test.test',
    'username' => 'test_test',
    'password' => '$2y$10$o40nm6gWHHUVYZole8u5YOzSDjrAT9xVT2iVFP6XC.lJXMDDjYaH6',
    'role' => 'jeune',
    'skills' => 
    array (
      0 => 
      array (
        'referent' => 
        array (
          'name' => 'referent_name',
          'firstname' => 'referent_firstname',
          'email' => 'referent@otacos.com',
          'situation' => 'OTacos_worker',
        ),
        'beginning' => '2000-01-01',
        'duration' => '1',
        'durationType' => 'mois',
        'environement' => 'Otacos company',
        'description' => 'fabrication de tacos',
        'socialSkills' => 
        array (
          0 => 'Fiable',
          1 => 'Réfléchie',
          2 => 'Patient',
          3 => 'Diplomate',
        ),
        'savoir-faire' => 
        array (
          0 => 'skill1',
          1 => 'skill2',
        ),
        'socialSkills_ref' => 
        array (
          0 => 'Ponctuel',
          1 => 'Organisé',
          2 => 'Diplomate',
          3 => 'Optimiste',
        ),
        'savoir-faire_ref' => 
        array (
          0 => 'skill3',
          1 => 'skill4',
        ),
        'comment' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc congue dapibus varius. Quisque lacinia a turpis quis condimentum. Morbi a ligula et ante feugiat faucibus vel eget diam. Nulla eu nisi tortor. Duis fringilla euismod odio ut molestie. Sed porta, mauris id dignissim malesuada, ex leo.',
        'status' => 'confirmed',
        'id' => 'b6f9cabee2b9a5e530735b185837ae40774b836bc64c2db88d89a8c192b413ed',
      ),
      1 => 
      array (
        'referent' => 
        array (
          'name' => 'referent_name',
          'firstname' => 'referent_firstname',
          'email' => 'referent@otacos.com',
          'situation' => 'OTacos_worker',
        ),
        'beginning' => '2000-01-01',
        'duration' => '1',
        'durationType' => 'jours',
        'environement' => 'Otacos company',
        'description' => 'fabrication de tacos',
        'socialSkills' => 
        array (
          0 => 'Ouvert d\'esprit',
          1 => 'A l\'écoute',
          2 => 'Communicatif',
          3 => 'Empathique',
        ),
        'savoir-faire' => 
        array (
          0 => 'skill1',
          1 => 'skill2',
        ),
        'status' => 'toConfirm',
        'id' => '21e2210910d8c5841f1ae1b4e766b67703e68df22a017f0914c1763e93bebcec',
      ),
    ),
  ),
); $other = array (
  '21e2210910d8c5841f1ae1b4e766b67703e68df22a017f0914c1763e93bebcec' => 
  array (
    'user' => 'test_test',
    'status' => 'referent',
  ),
  '0d576fbf738dfee6f71d1265b431bc84e7e129761b664ada3a8ace72a54c9f9e' => 
  array (
    'user' => 'test_test',
    'status' => 'consultant',
    'email' => 't@t.all',
    'skills' => 'all',
  ),
); ?>