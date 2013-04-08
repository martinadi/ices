<div class="row">
	<div class="span12">
		<?php /** @var BootActiveForm $form */
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		    'id'=>'MusicUploadForm-form',
		    'type'=>'horizontal')); ?>
		
			<fieldset>
				<legend>Update Music Data</legend>				
				<?php echo $form->textFieldRow($formModel, 'title'); ?>				
				<?php echo $form->textFieldRow($formModel, 'artist'); ?>
				<?php echo $form->textFieldRow($formModel, 'album'); ?>
				<?php echo $form->textFieldRow($formModel, 'year'); ?>
				<?php echo $form->textFieldRow($formModel, 'genre'); ?>
			</fieldset>
			
			<div class="form-actions">
			    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Upload')); ?>			    
			</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>
<audio controls src="<?php echo $this->createAbsoluteUrl('previewupload', array('file' => $file))?>" style="width: 100%"></audio>