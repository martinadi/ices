<?php
class MusicForm extends CFormModel{
	public $title;
	public $artist;
	public $album;
	public $genre;
	
	public function rules(){
		return array(
			array('title, artist', 'required'),
			array('genre', 'safe')
		);
	}
}