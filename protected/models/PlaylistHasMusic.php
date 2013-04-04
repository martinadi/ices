<?php

/**
 * This is the model class for table "playlist_has_music".
 *
 * The followings are the available columns in table 'playlist_has_music':
 * @property integer $playlist_id
 * @property integer $music_id
 * @property integer $queue
 */
class PlaylistHasMusic extends CActiveRecord
{
	public $music_title;
	public $music_artist;
	public $music_album;
	public $music_playtime;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PlaylistHasMusic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'playlist_has_music';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('playlist_id, music_id, queue', 'required'),
			array('playlist_id, music_id, queue', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('playlist_id, music_id, queue, music_title, music_artist, music_album, music_playtime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Music' => array(self::BELONGS_TO, 'Music', array('music_id' => 'music_id')),
			'Playlist' => array(self::BELONGS_TO, 'Playlist', array('playlist_id' => 'playlist_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'playlist_id' => 'Playlist',
			'music_id' => 'Music',
			'queue' => 'Queue',
			'music_title' => 'Title',
			'music_artist' => 'Artist',
			'music_album' => 'Album',
			'music_playtime' => 'Playtime'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array('Music', 'Playlist');
		$criteria->compare('t.playlist_id',$this->playlist_id);
		$criteria->compare('t.music_id',$this->music_id);
		
		$criteria->compare('Music.title', $this->music_title, true);
		$criteria->compare('Music.artist', $this->music_artist, true);
		$criteria->compare('Music.album', $this->music_album, true);
		$criteria->compare('Music.playtime', $this->music_playtime, true);
		
		$criteria->order = "queue Asc";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}