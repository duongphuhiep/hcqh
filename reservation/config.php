<?php
define('DBHOST', "hopcaquehuong.db.9404273.hostedresource.com");
define('DBLOGIN', "hopcaquehuong");
define('DBNAME', "hopcaquehuong");
define('DBPASS', "QueHuong.09");
define('NBPLACES', 450);
define('DEBUG', false);

function getGUID(){
	if (function_exists('com_create_guid')){
		return com_create_guid();
	}else{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		return md5(uniqid(rand(), true));
	}
}
