<?php
	$url = 'http://rest.gotye.com.cn/webIm/arclist/';
	$name = '';

	for($i=0; $i<200; $i++)
	{
		$name = '' . $i . '.gif';

		echo $url  . $name;
		echo "\n";

		if (!file_exists($name))
		{
			$d = file_get_contents($url  . $name);

			if ($d)
			{
				file_put_contents($name, $d);
			}
		}

	}


?>