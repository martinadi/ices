<?php

/**
 * This is the model class for table "music".
 *
 * The followings are the available columns in table 'music':
 * @property integer $music_id
 * @property string $filename
 * @property string $playtime_string
 * @property string $playtime_second
 * @property string $bitrate
 * @property string $title
 * @property string $artist
 * @property string $album
 * @property string $genre
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Playlist[] $playlists
 */
class Music extends CActiveRecord
{
	public $in_array;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Music the static model class
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
		return 'music';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, artist', 'required'),
			array('filename, title, artist, album, genre', 'length', 'max'=>128),
			array('playtime_string', 'length', 'max'=>8),
			array('bitrate', 'length', 'max'=>16),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('music_id, filename, playtime_string, playtime_second, bitrate, title, artist, album, genre, created, updated', 'safe', 'on'=>'search'),
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
			'playlists' => array(self::MANY_MANY, 'Playlist', 'playlist_has_music(music_id, playlist_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'music_id' => 'Music',
			'filename' => 'Filename',
			'playtime_string' => 'playtime String',
			'playtime_second' => 'playtime Second',
			'bitrate' => 'Bitrate',
			'title' => 'Title',
			'artist' => 'Artist',
			'album' => 'Album',
			'genre' => 'Genre',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('music_id',$this->music_id);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('playtime_string',$this->playtime_string,true);
		$criteria->compare('playtime_second',$this->playtime_second,true);
		$criteria->compare('bitrate',$this->bitrate,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('artist',$this->artist,true);
		$criteria->compare('album',$this->album,true);
		$criteria->compare('genre',$this->genre,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function findNotIn(){
		$criteria=new CDbCriteria;
		
		$criteria->addNotInCondition('music_id', $this->in_array);
		
		$criteria->compare('playtime_string',$this->playtime_string,true);
		$criteria->compare('playtime_second',$this->playtime_second,true);
		$criteria->compare('bitrate',$this->bitrate,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('artist',$this->artist,true);
		$criteria->compare('album',$this->album,true);
		$criteria->compare('genre',$this->genre,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function findIn(){
		$criteria=new CDbCriteria;
	
		$criteria->addInCondition('music_id', $this->in_array);
	
		$criteria->compare('playtime_string',$this->playtime_string,true);
		$criteria->compare('playtime_second',$this->playtime_second,true);
		$criteria->compare('bitrate',$this->bitrate,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('artist',$this->artist,true);
		$criteria->compare('album',$this->album,true);
		$criteria->compare('genre',$this->genre,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function beforeSave() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
	
		$this->updated = new CDbExpression('NOW()');
	
		return parent::beforeSave();
	}
}