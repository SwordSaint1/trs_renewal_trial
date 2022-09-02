<div class="modal" tabindex="-1" role="dialog" id="update_announcement">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b>Update Announcement</b> <br>
          
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <input type="hidden" name="id_announcement" id="id_announcement">
            <label>Announcement Date:</label>  <input type="date" name="announcement_date_update" id="announcement_date_update" class="form-control">
            </div>
    <div class="col s12">
       <label>Announcement Content:</label> 
       <input type="text" class="form-control" id="content_update">
         
       </div>
    </div>     
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-12">
              <button type="button" class="btn btn-danger"  onclick="delete_announcement()"
        class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()">Delete Announcement</button>
   
                <button type="button" class="btn btn-primary"  onclick="update_announcement()"
        class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()">Update Announcement</button>
          </div>
        </div>
     
       
        
       
      </div>
    </div>
  </div>
</div>
