<?php
class MusicForm extends CFormModel{
	public $title;
	public $artist;
	public $album;
	public $year;
	public $genre;
	
	public function rules(){
		return array(
			array('title, artist', 'required'),
			array('album, genre, year', 'safe')
		);
	}
}