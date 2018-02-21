
<div class="container">
    <div class="row">
    <div class="col-md-10">

    <div id="calendar">
</div>


    </div>
    </div>
    </div>


<script type="text/javascript">
$(document).ready(function() {
var timez = Intl.DateTimeFormat().resolvedOptions().timeZone;
$('#calendar').fullCalendar({
header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
eventSources: [
            {
                color: '#18b9e6',   
                textColor: '#000000',
                events: function(start, end, timezone, callback) {
                 $.ajax({
                 url: '<?php echo base_url() ?>tutor/get_calendar_courses',
                 dataType: 'json',
                 method:'post',
                 data:{timezone:timez},
                 success: function(msg) {
                     var events = msg.events;
                     callback(events);
                 }
                 });
             }
            }
        ],
        dayClick: function(date, jsEvent, view) {
        date_last_clicked = $(this);
        $(this).css('background-color', '#bed7f3');
        $("#timezone").val(timez);
        $('#addModal').modal();
        var d = new Date(date);
        $("#start_date").val(formatDate(d));
    },
    eventClick: function(event, jsEvent, view) {
    $("#edittimezone").val(timez);
    var el = document.getElementById("edit_course_id");
for(var i=0; i<el.options.length; i++) {
  if ( el.options[i].text == event.title ) {
    el.selectedIndex = i;
    break;
  }
}
          //$('#edit_course_id').val($('#edit_course_id').find('option[text="'+event.title+'"]').val());
          $('#editdescription').val(event.description);
          $('#editstart_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
          $('#event_id').val(event.id);
          $('#editModal').modal();
       },
    });
});
function formatDate(date) {
  

  var day = date.getDate();
  var monthIndex = date.getMonth()+1;
  var year = date.getFullYear();

  return year + '-' + monthIndex + '-' + day;
}

</script>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Course</h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("tutor/add_course"), array("class" => "form-horizontal")) ?>
      <input type="hidden" id="timezone" name="timezone"/>
      <input type="hidden" id="start_date" name="start_date"/>
      <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Course Name</label>
                <div class="col-md-8 ui-front">
                    <?php echo $course_id;?>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Description</label>
                <div class="col-md-8 ui-front">
                    <textarea class="form-control" name="description"></textarea>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Course Time</label>
                <div class="col-md-8">
                    <input type="time" class="form-control" name="start_time" id="start_time">
                </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Course">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Course</h4>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url("tutor/edit_course"), array("class" => "form-horizontal")) ?>
      <input type="hidden" id="edittimezone" name="edittimezone"/>
      <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Course Name</label>
                <div class="col-md-8 ui-front">
                    <?php echo $edit_course_id;?>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Description</label>
                <div class="col-md-8 ui-front">
                    <textarea  class="form-control" name="editdescription" id="editdescription"></textarea>
                </div>
        </div>
        <div class="form-group">
                <label for="p-in" class="col-md-4 label-heading">Course Time</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="editstart_date" id="editstart_date">
                </div>
        </div>
        
            <input type="hidden" name="eventid" id="event_id" value="0" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Update Event">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>