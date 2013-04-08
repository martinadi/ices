<?php
class MusicController extends Controller{
	public function actionUpload(){
		$this->breadcrumbs=array(
				'Music' => array('index'),
				'Upload'
		);
		
		$formModel = new MusicUploadForm;
		
		if(isset($_POST['ajax']) && $_POST['ajax'] ==='MusicUploadForm-form'){
			echo CActiveForm::validate($formModel);
			Yii::app()->end();
		}
		
		if(isset($_POST['MusicUploadForm'])){
			$formModel->attributes=$_POST['MusicUploadForm'];
			$formModel->file=CUploadedFile::getInstance($formModel,'file');
			// validate user input and redirect to the previous page if valid
			if($formModel->validate()){
				$file_name = $formModel->file->name;
				$file_size = $formModel->file->size;
				$file_tmp = $formModel->file->tempName;
				$file_ext = $formModel->file->extensionName;
				$file_type = $formModel->file->type;
				
				$time = time();
				$upload_dir = Yii::app()->params['upload_tmp'];

				if(!is_dir($upload_dir)){
					mkdir($upload_dir);
				}
				
				$formModel->file->saveAs($upload_dir . $time);
				$this->redirect(array('saveUpload', 'file' => $time));
			}
		}
		
		$this->render('upload', array('formModel' => $formModel));
	}
	
	public function actionSaveUpload($file){
		$this->breadcrumbs=array(
				'Music' => array('index'),
				'Upload' => array('upload'),
				'Update Music Data'
				
		);
		
		Yii::import('ext.getid3.*', true);
		
		$upload_dir = Yii::app()->params['upload_tmp'];
		
		if(!file_exists($upload_dir.$file)){
			throw new CHttpException(404, 'file not found');
		}
		
		$getid3 = new getid3();
		
		$fileinfo = $getid3->analyze($upload_dir.$file);
		
		//print_r($fileinfo);exit;
		
		// seharusnya $fileinfo['fileformat'] akan tetapi sementara di haruskan mp3 dulu. 
		// add issue id3v2
		switch ("mp3") {
			case 'ogg':
				$tag = 'vorbiscomment';
				break;
			case 'mp3':
				$AllowedTagFormats = array('id3v1', 'id3v2', 'id3v2.2', 'id3v2.3', 'id3v2.4'); //'ape', 'lyrics3'
				arsort($AllowedTagFormats);
				$tag = '';

				if(isset($fileinfo['tags'])){
					if(count($fileinfo['tags'] > 1)){
						arsort($fileinfo['tags']);
						reset($fileinfo['tags']);
						$tag = key($fileinfo['tags']);
					}else{
						foreach($AllowedTagFormats as $allowedTag){
							if(isset($fileinfo[$allowedTag])){
								$tag = $allowedTag;
								break;
							}
						}
					}
				}else{
					foreach($AllowedTagFormats as $allowedTag){
						if(isset($fileinfo[$allowedTag])){
							$tag = $allowedTag;
							break;
						}
					}
				}
				
				if($tag == ''){
					$tag = 'id3v1';
				}
				break;
		}
		
		if(isset($fileinfo['tags'])){
			if(!isset($_POST['MusicForm'])){
				$_POST['MusicForm']['title'] = (isset($fileinfo['tags'][$tag]['title'][0]))?$fileinfo['tags'][$tag]['title'][0]:null;
				$_POST['MusicForm']['artist'] = (isset($fileinfo['tags'][$tag]['artist'][0]))?$fileinfo['tags'][$tag]['artist'][0]:null;
				$_POST['MusicForm']['album'] = (isset($fileinfo['tags'][$tag]['album'][0]))?$fileinfo['tags'][$tag]['album'][0]:null;
				$_POST['MusicForm']['year'] = (isset($fileinfo['tags'][$tag]['year'][0]))?$fileinfo['tags'][$tag]['year'][0]:null;
				$_POST['MusicForm']['genre'] = (isset($fileinfo['tags'][$tag]['genre'][0]))?$fileinfo['tags'][$tag]['genre'][0]:null;
			}	
		}else{
			if(!isset($_POST['MusicForm'])){
				$_POST['MusicForm']['title'] = (isset($fileinfo[$tag]['comments']['title'][0]))?$fileinfo[$tag]['comments']['title'][0]:null;
				$_POST['MusicForm']['artist'] = (isset($fileinfo[$tag]['comments']['artist'][0]))?$fileinfo[$tag]['comments']['artist'][0]:null;
				$_POST['MusicForm']['album'] = (isset($fileinfo[$tag]['comments']['album'][0]))?$fileinfo[$tag]['comments']['album'][0]:null;
				$_POST['MusicForm']['year'] = (isset($fileinfo[$tag]['comments']['year'][0]))?$fileinfo[$tag]['comments']['year'][0]:null;
				$_POST['MusicForm']['genre'] = (isset($fileinfo[$tag]['comments']['genre'][0]))?$fileinfo[$tag]['comments']['genre'][0]:null;
			}
		}
		
				
		
		$formModel = new MusicForm;
		
		if(isset($_POST['ajax']) && $_POST['ajax'] ==='MusicForm-form'){
			echo CActiveForm::validate($formModel);
			Yii::app()->end();
		}
		
		if(isset($_POST['MusicForm'])){
			$formModel->attributes=$_POST['MusicForm'];
			// validate user input and redirect to the previous page if valid
			if($formModel->validate()){
				$tagwriter = new write;
				$tagwriter->filename = $upload_dir.$file;
				$TagData['title'][] 	= strtolower($_POST['MusicForm']['title']);
				$TagData['artist'][] 	= strtolower($_POST['MusicForm']['artist']);
				$TagData['album'][] 	= strtolower($_POST['MusicForm']['album']);
				$TagData['year'][] 	= strtolower($_POST['MusicForm']['year']);
				$TagData['genre'][] 	= strtolower($_POST['MusicForm']['genre']);
				$tagwriter->tag_data 	= $TagData;
				
				//$tagwriter->tagformats     = array($tag);
				
				if ($tagwriter->WriteTags()) {
					rename($upload_dir.$file, Yii::app()->params['music_dir'].$file);
					
					$model = new Music;
					$model->filename = $file;
					$model->title = strtolower($_POST['MusicForm']['title']);
					$model->artist = strtolower($_POST['MusicForm']['artist']);
					$model->album = strtolower($_POST['MusicForm']['album']);
					$model->year = strtolower($_POST['MusicForm']['year']);
					$model->genre = strtolower($_POST['MusicForm']['genre']);
					$model->playtime_string = (isset($fileinfo['playtime_string']))?$fileinfo['playtime_string']:null;
					$model->playtime_second = (isset($fileinfo['playtime_seconds']))?$fileinfo['playtime_seconds']:null;
					$model->bitrate = (isset($fileinfo['bitrate']))?$fileinfo['bitrate']:null;
					
					
					echo 'Successfully wrote tags<BR>';
					if($model->save()){
						echo 'all success';
						$music_id = Yii::app()->db->getLastInsertID();
					}
					if (!empty($tagwriter->warnings)) {
						echo 'There were some warnings:<BLOCKQUOTE STYLE="background-color:#FFCC33; padding: 10px;">'.implode('<br><br>', $tagwriter->warnings).'</BLOCKQUOTE>';
						exit;
					}
					
				} else {
					
					/* if(in_array('Tag format "id3v1" is not allowed on "" files', $tagwriter->errors)){
						echo "id3v1";
					}
					
					if(in_array('Tag format "id3v2" is not allowed on "mp3.mp3" files', $tagwriter->errors)){
						echo "id3v2 error";
					} */
					echo 'Failed to write tags!<BLOCKQUOTE STYLE="background-color:#FF9999; padding: 10px;">'.implode('<br><br>', $tagwriter->errors).'</BLOCKQUOTE>';
					exit;
				}
				$this->redirect(array('view', 'id' => $music_id));
				exit;
			}
		}
		
		$this->render('saveupload', array('formModel' => $formModel, 'file' => $file));
	}
		
	public function actionView($id){
		$model = Music::model()->findByPk($id);
		
		$this->breadcrumbs=array(
				'Music' => array('index'),
				$model->album . ' - '. $model->title
		);
		
		
		$this->render('view', array('model' => $model));
	}
	
	public function actionIndex(){
		$this->breadcrumbs=array(				
				'Music',
		);
		$model=new Music('search');
		$model->unsetAttributes();  // clear any default values		
		if(isset($_GET['Music']))
			$model->attributes=$_GET['Music'];
		
		$this->render('index',array(
				'model'=>$model,
		));
	}
	
	public function actionUpdate($id){
		Yii::import('ext.getid3.*', true);
		
		$model = Music::model()->findByPk($id);
		$file = $model->filename;
		
		$music_dir = Yii::app()->params['music_dir'];
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Music']))
		{
			$model->attributes=$_POST['Music'];
			
			$getid3 = new getid3();
			$tagwriter = new write;
			$tagwriter->filename = $music_dir.$file;
			$TagData['title'][] 	= strtolower($_POST['Music']['title']);
			$TagData['artist'][] 	= strtolower($_POST['Music']['artist']);
			$TagData['album'][] 	= strtolower($_POST['Music']['album']);
			$TagData['genre'][] 	= strtolower($_POST['Music']['genre']);
			$tagwriter->tag_data 	= $TagData;
			
			
			if ($tagwriter->WriteTags()) {
				if($model->save()){
					$this->redirect(array('view','id'=>$model->music_id));
				}
			}
		}
		
		$this->render('update',array(
				'formModel'=>$model
		));
	}
	
	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest)
		{
			$model = Music::model()->findByPk($id);
			
			$music_dir = Yii::app()->params['music_dir'];
			$file = $model->filename;
			if($model->delete()){
				unlink($music_dir . $file);
			}
		
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionPreviewUpload($file){
	
		$music_dir = Yii::app()->params['upload_tmp'];
		$file = $music_dir . $file;
	
		header("Content-Type: application/ogg");
		header('Content-Length:'.filesize($file));
		header('Content-Disposition: inline; filename="stream.file"');
		header('X-Pad: avoid browser bug');
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Expires: -1");
		ob_clean();
		flush();
		readfile($file);
	
	}
	
	public function actionPreview($id){
		
		$model = Music::model()->findByPk($id);
		
		$music_dir = Yii::app()->params['music_dir'];
		$file = $music_dir . $model->filename;
		
		//header("Content-Type: application/ogg");
		header('Content-Length:'.filesize($file));
		header('Content-Disposition: inline; filename="stream.file"');
		header('X-Pad: avoid browser bug');
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Expires: -1");
		ob_clean();
		flush();
		readfile($file);
		
	}
}
