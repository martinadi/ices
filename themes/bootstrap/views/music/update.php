<div class="row">
	<div class="span12">
		<?php /** @var BootActiveForm $form */
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		    'id'=>'MusicUploadForm-form',		
			
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
		    'type'=>'horizontal')); ?>
		
			<fieldset>
				<legend>Update Music Data</legend>				
				<?php echo $form->textFieldRow($formModel, 'title'); ?>				
				<?php echo $form->textFieldRow($formModel, 'artist'); ?>
				<?php echo $form->textFieldRow($formModel, 'album'); ?>
				<?php echo $form->textFieldRow($formModel, 'genre'); ?>
			</fieldset>
			
			<div class="form-actions">
			    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Update')); ?>			    
			</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>