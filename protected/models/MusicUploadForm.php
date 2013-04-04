<?php
class MusicUploadForm extends CFormModel{
	public $file;
	
	public function rules(){
		return array(
			array('file', 'file', 'types' => 'mp3')
		);
	}
	
	public function attributeLabels(){
		return array('file' => 'File');
	}
}