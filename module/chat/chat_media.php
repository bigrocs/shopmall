<?php
include_once("../../includes/global.php");

//header('Content-type: application/json');

$upload = new HTTP_Upload('en');
$files  = $upload->getFiles();

if (PEAR::isError($files))
{
	echo json_encode( array(
						  'upload' => false,
						  'filetype'    => 'error',
						  'maxsize'    => false
					  ) );

	die();
}

foreach ($files as $file)
{
	if ($file->isValid())
	{
		$path = $config['webroot'] . '/uploadfile/chat/';
		if (!file_exists($path))
		{
			mkdir($path);
		}

		$file->setName('uniq');

		$file_name = $file->moveTo($path);

		if (PEAR::isError($file_name))
		{
			echo json_encode( array(
								  'upload' => false,
								  'filetype'    => false,
								  'maxsize'    => false
							  ) );

			die();
		}



		$data['attachment_mime_type']  = $file->upload['type']; // 上传的附件类型

		$data_row['mime'] = $file->upload['type'];
		$data_row['type'] = 'image';
		$data_row['subtype'] = $file->upload['upload'];


		$url = $config['weburl'] . '/uploadfile/chat/' . $file->upload['name'];

		$file_row = array(
			'upload' => 'success',
			'url'    => $url,
			'filetype'    => false,
			'maxsize'    => false
		);

		echo json_encode($file_row);
		die();
	}
	else
	{
		echo json_encode( array(
							  'success' => false,
							  'data'    => array(
								  'message'  => _('发生错误！'),
								  'filename' => $_FILES['upload']['name'],
							  )
						  ) );

		die();
	}
}

?>