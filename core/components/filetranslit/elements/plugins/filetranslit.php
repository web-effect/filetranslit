<?php
/**
 * @author Anton Andersen <anton.a.andersen@gmail.com>
 *
 * This plugin transliterates filenames on upload via MODX filemanager.
 * It should be bent to the OnFileManagerUpload event.
 * Project page: https://github.com/TriAnMan/filetranslit
 */
$currentdoc = $modx->newObject('modResource');
foreach ($files as &$file) {
	if ($file['error'] == 0) {
		$newName = '';
		foreach(explode(".",$file['name']) as $i=>$p)$newName .= ($i>0?'.':'').$currentdoc->cleanAlias($p);

		//file rename logic
		if ($file['name'] !== $newName) {
			$arDirFiles = $source->getObjectsInContainer($directory);
			foreach ($arDirFiles as &$dirFile){
				if($dirFile['name']===$newName){
					//delete file if there is one with new name
					$source->removeObject($directory . $newName);
				}
			}
			//transliterate uploaded file
			$source->renameObject($directory . $file['name'], $newName);
			//add changed paths back
			$uploaded = array_keys($source->uploaded_objects,$file['name']);
			foreach($uploaded as $upload)$source->uploaded_objects[$upload]=$newName;
		}
	}
}
