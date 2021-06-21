<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

$execSQL = 'SELECT id FROM %s ORDER BY '.Application::get('POST.LIST_SORT').' LIMIT '.Application::get('POST.LIST_SIZE');
$Statement = $Database->query(sprintf($execSQL, Post\Attribute::TABLE));

$postIDs = $Statement->fetchAll($Database::FETCH_COLUMN);

foreach($postIDs as $postID) {
	try {
		$Post = Post\Factory::build($postID);
		$User = User\Factory::build($Post->get('user'));
		$templates[] = generatePostItemTemplate($Post, $User);
	}
	catch(Post\Exception $Exception){}
	catch(User\Exception $Exception){}
}

#===============================================================================
# Build document
#===============================================================================
$HomeTemplate = Template\Factory::build('home');
$HomeTemplate->set('PAGINATION', [
	'HTML' => generatePostNaviTemplate(1)
]);
$HomeTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $HomeTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => Application::get('BLOGMETA.HOME'),
	'DESC' => Application::get('BLOGMETA.NAME').' – '.Application::get('BLOGMETA.DESC'),
	'PERM' => Application::getURL()
]);

echo $MainTemplate;
