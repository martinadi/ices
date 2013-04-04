<div class="row">
	<div class="span12">
		<?php /** @var BootActiveForm $form */
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		    'id'=>'MusicUploadForm-form',		
			
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		    'type'=>'horizontal')); ?>
		
			<fieldset>
				<legend>Upload Music</legend>				
				<?php echo $form->fileFieldRow($formModel, 'file'); ?>
				
			</fieldset>
			
			<div class="form-actions">
			    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Upload')); ?>			    
			</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>