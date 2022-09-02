<div class="modal" tabindex="-1" role="dialog" id="newannouncement">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b>Register Announcement</b> <br>
          
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-12">
            <label>Announcement Date:</label>  <input type="date" name="announcement_date" id="announcement_date" class="form-control" value="<?=$server_date_only;?>">
            </div>
    <div class="col s12">
       <label>Announcement Content:</label> 
       <input type="text" class="form-control" id="content">
         
       </div>
    </div>     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"  onclick="register_announcements()"
        class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()">Register Announcement</button>
       
      </div>
    </div>
  </div>
</div>
