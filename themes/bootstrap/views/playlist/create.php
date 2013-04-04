<div class="row">
	<div class="span12">
		<?php /** @var BootActiveForm $form */
		$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		    'id'=>'Playlist-form',		
		    'type'=>'horizontal')); ?>
		
			<fieldset>
				<legend>Update Music Data</legend>				
				<?php echo $form->textFieldRow($model, 'name'); ?>				
			</fieldset>
			
			<div class="form-actions">
			    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Submit')); ?>			    
			</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>