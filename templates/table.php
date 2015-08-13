<?php
global $myListTable;
echo '</pre><div class="wrap"><h2>My List Table Test</h2>'. sprintf('<a href="?page=%s&action=%s" class="button-primary">Add new</a>',$_REQUEST['page'],'add'); 
$myListTable->prepare_items(); 
?>
 <form method="post">
   	<input type="hidden" name="page" value="ttest_list_table">
   	<?php
   	$myListTable->search_box( 'search', 'search_id' );
 	$myListTable->display(); 
 echo '</form></div>'; 