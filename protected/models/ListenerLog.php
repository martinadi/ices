<?php

/**
 * This is the model class for table "listener_log".
 *
 * The followings are the available columns in table 'listener_log':
 * @property integer $listener_log_id
 * @property integer $listener_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $begin_listening
 * @property integer $conected_time
 * @property string $countryName
 * @property string $regionName
 * @property string $city
 * @property double $latitude
 * @property double $longitude
 * @property string $created
 * @property string $updated
 */
class ListenerLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ListenerLog the static model class
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
		return 'listener_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('listener_id, begin_listening, conected_time', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('ip', 'length', 'max'=>45),
			array('user_agent', 'length', 'max'=>255),
			array('countryName, regionName, city', 'length', 'max'=>128),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('listener_log_id, listener_id, ip, user_agent, begin_listening, conected_time, countryName, regionName, city, latitude, longitude, created, updated', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'listener_log_id' => 'Listener Log',
			'listener_id' => 'Listener',
			'ip' => 'Ip',
			'user_agent' => 'User Agent',
			'begin_listening' => 'Begin Listening',
			'conected_time' => 'Conected Time',
			'countryName' => 'Country Name',
			'regionName' => 'Region Name',
			'city' => 'City',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
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

		$criteria->compare('listener_log_id',$this->listener_log_id);
		$criteria->compare('listener_id',$this->listener_id);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('begin_listening',$this->begin_listening);
		$criteria->compare('conected_time',$this->conected_time);
		$criteria->compare('countryName',$this->countryName,true);
		$criteria->compare('regionName',$this->regionName,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
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
	
	public function log($listener_id, $ip, $user_agent, $conected_time ){
	
		$db = Yii::app()->db;
		$begin_listening = time() - $conected_time;
	
		$betwen_start = $begin_listening - 1;
		$betwen_end = $begin_listening + 1;
		
		$location = Yii::app()->geoip->lookupLocation($ip);
	
		//cari record lama
		$record = $db->createCommand()
		->select('listener_log_id')
		->from($this->tableName())
		->where('listener_id=:listener_id', array(':listener_id' => $listener_id))
		->andWhere('ip=:ip', array(':ip' => $ip))
		->andWhere('user_agent=:user_agent', array(':user_agent' => $user_agent))
		->andWhere('begin_listening between :start and :end', array(':start' => $betwen_start, ':end' => $betwen_end))
		->queryScalar();
			
		if($record){
			$db->createCommand()->update(
					$this->tableName(),
					array(	'conected_time' => $conected_time,
							'updated' => new CDbExpression('NOW()')),
					'listener_log_id=:listener_log_id', array(':listener_log_id' => $record));
		}else{
			$db->createCommand()->insert(
					$this->tableName(),
					array(	'listener_id' => $listener_id,
							'ip' => $ip,
							'user_agent' => $user_agent,
							'begin_listening' => $begin_listening,
							'conected_time' => $conected_time,
							
							'countryName' => $location->countryName, 
							'regionName' => $location->regionName, 
							'city' => $location->city, 
							'latitude' => $location->latitude, 
							'longitude' => $location->longitude,
							
							'created' => new CDbExpression('NOW()'),
							'updated' => new CDbExpression('NOW()')));
		}
	}
}