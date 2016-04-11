<?php
$url = 'http://localhost/connections/web/app_dev.php';
$I = new AcceptanceTester($scenario);
$badUsername = "randomBadUsername";
$badPassword = "randomBadPassword";
$goodUsername = "Elenora.Schowalter";
$goodPassword = "123321";

$newUsername = "Elenora.Schowalter";
$newPassword = "123321";
$newEmail = "mail@mail.com";
$newName = "petar";
$newImage = "randomImage";
$otherNewUsername = "someNewUser";


$I->amOnPage('/login');

$I->click('.login-connections');
$I->see('Wrong username / password');

$I->fillField('_username', $badUsername);
$I->fillField('_password', $badPassword);
$I->click('.login-connections');
$I->see('Wrong username / password');

$I->fillField('_username', $goodUsername);
$I->fillField('_password', $goodPassword);
$I->click('.login-connections');
$I->amOnPage('/');
$I->see('Hello, ' .$goodUsername);

$I->click('.logout-button');
$I->amOnPage('/login');

//$I->fillField('Username', $newUsername);
//$I->fillField('Password', $newPassword);
//$I->fillField('Email', $newEmail);
//$I->fillField('Name', $newName);
//$I->fillField('Image', $newImage);
//$I->sendPOST('/api/register');
//$I->see('Username or Email already exists');
//
//$I->fillField('Username', $otherNewUsername);
//$I->fillField('Password', $newPassword);
//$I->fillField('Email', $newEmail);
//$I->fillField('Name', $newName);
//$I->fillField('Image', $newImage);
//$I->sendPOST('/api/register');
//$I->see('You have successfully registered !!!');

$I->fillField('_username', $newUsername);
$I->fillField('_password', $newPassword);
$I->click('.login-connections');
$I->see('Hello, ' .$newUsername);

$I->sendGET($url.'/api/user-details/0');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
//$I->seeResponseContainsJson([
//    'Id' => '1',
//    'Address' => "5182 Heidenreich Trail Suite 237 New Eliza, I", //etc
//]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET($url.'/api/user-direct-friends/0');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET($url.'/api/user-fof/0');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET($url.'/api/user-suggested/0');
$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([]);
$I->haveHttpHeader('Content-Type', 'application/json');

$I->sendPOST('/api/users', ['Order' => null, 'Page' => 1]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['TotalRecords' => '"111001"']);

$I->sendPOST('/api/follow', ['Id' => 1]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['success' => 'You have successfully made new connection!']);

$I->sendDELETE('/api/unfollow', ['Id' => 1]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['success' => 'You have successfully unfollowed!']);

$I->sendPOST('/api/users', ['Order' => 'By Followers', 'Page' => 1]);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['TotalRecords' => '"111001"']);

//
//$id = $I->grabDataFromResponseByJsonPath('id');
////\Codeception\Util\Debug::debug($id[0]);die();
//



































