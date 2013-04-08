<?php 
Yii::app()->clientScript->registerScriptFile(
$this->createAbsoluteUrl('themes/bootstrap/fullcalendar/jquery-ui-1.10.2.custom.min.js'), CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(
$this->createAbsoluteUrl('themes/bootstrap/fullcalendar/fullcalendar.min.js'), CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerCssFile($this->createAbsoluteUrl('themes/bootstrap/fullcalendar/fullcalendar.css'), 'screen');
Yii::app()->clientScript->registerCssFile($this->createAbsoluteUrl('themes/bootstrap/fullcalendar/fullcalendar.print.css'), 'print');
?>
<style>
		
	#external-events {
		padding: 0 10px;
		border: 1px solid #ccc;
		background: #eee;
		text-align: left;
		}
		
	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
		}
		
	.external-event { /* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
		}
		
	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
		}
		
	#external-events p input {
		margin: 0;
		vertical-align: middle;
		}

	#calendar {
/* 		float: right; */
		}

</style>

<script>
	var selectedEvent;	

	var time_last, time_diff;

	function currentTime(){
		time_diff = new Date().getTime() - time_last;
		return time_diff;
	}
	
	$(document).ready(function() {

		time_last = new Date().getTime();
	
	
		/* initialize the external events
		-----------------------------------------------------------------*/
	
		$('#external-events div.external-event').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()), // use the element's text as the event title
				duration: $.trim($(this).attr('data-duration')),
				isNew: Boolean($.trim($(this).attr('data-new'))),
				playlistId:	$.trim($(this).attr('data-playlistId'))			
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});
	
	
		/* initialize the calendar
		-----------------------------------------------------------------*/
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			slotMinutes:15,
			defaultView: 'agendaDay',
			allDaySlot: false,
			disableResizing: true,
			editable: true,
			allDayDefault: false,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			eventSources: [
			               {
				               url: '<?php echo $this->createUrl('ajaxfeed');?>',
				               color: 'green',				               
				           }
			],
			drop: function(date, allDay) { // this function is called when something is dropped
			
				// retrieve the dropped element's stored Event Object
				var originalEventObject = $(this).data('eventObject');
				
				// we need to copy it, so that multiple events don't have a reference to the same object
				var copiedEventObject = $.extend({}, originalEventObject);
				
				// assign it the date that was reported
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				copiedEventObject.id = new Date().getTime();

				$.post(
					'<?php echo $this->createUrl('GetEndEvent');?>',
					{date_start: date, duration: originalEventObject.duration,<?php echo Yii::app()->request->csrfTokenName?>: '<?php echo Yii::app()->request->csrfToken?>'},
					function(data){
						//console.log(data);
						copiedEventObject.end = data.date_end;
						//console.log(copiedEventObject);
						$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

						selectedEvent = copiedEventObject;
						$('#event-id').val(copiedEventObject.id);
						
						$('#form-update').fadeIn();
						$('#playlistName').val(copiedEventObject.title);
					},'json'
				);
				
				// render the event on the calendar
				// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
				
				
				// is the "remove after drop" checkbox checked?
				if ($('#drop-remove').is(':checked')) {
					// if so, remove the element from the "Draggable Events" list
					$(this).remove();
				}
				
			},
			eventClick: function(event, jsEvent, view){
				if(typeof event.isNew === "undefined"){
					event.isNew = false;
				}
					
				selectedEvent = event;
				$('#event-id').val(event.id);
				$('#form-update').fadeIn();
				$('#playlistName').val(selectedEvent.title);
			}
			
		});
		
		
	});

	function removeSelectedEvent(){
		//console.log(selectedEvent);
		if(selectedEvent.isNew){
			$('#calendar').fullCalendar( 'removeEvents', selectedEvent.id );
			
		}else{			
			$.post(
				'<?php echo $this->createUrl('RemoveEvent');?>',
				{	id: selectedEvent.id,
					<?php echo Yii::app()->request->csrfTokenName?>: '<?php echo Yii::app()->request->csrfToken?>'
				},
				function (data){
					if(data.status){
						$('#calendar').fullCalendar('removeEvents', selectedEvent.id );
						$('#calendar').fullCalendar('refetchEvents');						
					}
				}, 'json'
			);
		}
		$('#form-update').fadeOut();
	}

	function saveSelectedEvent(){
		if(selectedEvent.isNew){
			$.post(
				'<?php echo $this->createUrl('SaveEvent');?>',
				{	playlistId: selectedEvent.playlistId, 
					start: selectedEvent.start, 
					end: selectedEvent.end, 
					<?php echo Yii::app()->request->csrfTokenName?>: '<?php echo Yii::app()->request->csrfToken?>'
				},
				function (data){
					if(data.status){
						$('#calendar').fullCalendar('removeEvents', selectedEvent.id );
						$('#calendar').fullCalendar('refetchEvents');
						
					}
				}, 'json'
			);
		}else{
			$.post(
				'<?php echo $this->createUrl('UpdateEvent');?>',
				{	id: selectedEvent.id, 
					start: selectedEvent.start, 
					end: selectedEvent.end, 
					<?php echo Yii::app()->request->csrfTokenName?>: '<?php echo Yii::app()->request->csrfToken?>'
				},
				function (data){
					if(data.status){
						$('#calendar').fullCalendar('removeEvents', selectedEvent.id );
						$('#calendar').fullCalendar('refetchEvents');
						
					}
				}, 'json'
			);
		}		
		$('#form-update').fadeOut();
	}

</script>

<div class="row-fluid">
	<div class="span2">
		<div id='external-events'>
			<h4>Playlist</h4>
			<?php foreach ($playlists as $playlist):?>
				<div class='external-event' 
						data-new="true"
						data-duration="<?php echo $playlist->duration?>" 						
						data-playlistId="<?php echo $playlist->playlist_id?>">
					<?php echo $playlist->name?>
				</div>
			<?php endforeach;?>						
			<p>
				<input type='checkbox' id='drop-remove' /> <label for='drop-remove'>remove
					after drop</label>
			</p>
		</div>
	</div>
	<div class="span3">
		<div class="span12" id="form-update" style="display: none;">
			<form class="well form-vertical" id="verticalForm" action="/yii-bootstrap/" method="post">
			
				<input type="hidden" id="event-id">
				
				<label for="TestForm_textField">Schedule action for playlist</label>
				<input class="span12" name="TestForm[textField]" id="playlistName" type="text" value="aa" readonly="readonly"> 
				
				<button type="button" class="btn btn-primary" style="float: right; margin-left: 10px;" onclick="saveSelectedEvent()">Save</button>
				<button type="button" class="btn btn-danger" style="float: right;" onclick="removeSelectedEvent()" >Remove</button>
				<div class="clear"></div>
			</form>
		</div>
		
	</div>
	<div class="span7">
		<div id='calendar'></div>
	</div>
</div>
