
<?php include 'plugins/navbar.php';?>
<?php include 'plugins/sidebar/dashboardbar.php';?>
  <!-- Main Sidebar Container -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Announcement Board</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Announcement Board</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                <div class="card-body">
                 <?php 
                  require '../../process/conn.php';
                  $query ="SELECT * FROM trs_renewal_announcement GROUP BY id ORDER BY announcement_date DESC";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  if ($stmt->rowCount() > 0) {
                    foreach($stmt->fetchALL() as $j){
                      $date = $j['announcement_date'];

                      if ($date == $server_date_only) {
                          echo' <div class="row">
                        <div class="col-sm-12">

                         <h4 id="announcement_date'.$j['id'].'" style="text-align:center; color:blue;">'.$j['announcement_date'].'</h4>
                        <h4 id="content'.$j['id'].'" style="text-align:justify; color:blue;">'.$j['content'].' 
                        </h4>

                        <hr>
                        </div>
                      </div>';
                            }else{
                                echo' <div class="row">
                        <div class="col-sm-12">

                         <h4 id="announcement_date'.$j['id'].'" style="text-align:center;">'.$j['announcement_date'].'</h4>
                        <h4 id="content'.$j['id'].'" style="text-align:justify;">'.$j['content'].' 
                        </h4>

                        <hr>
                        </div>
                      </div>';
                            }

                         
                        }
                  }else{
                       echo' <div class="row">
                        <div class="col-sm-12">

                         <h4 style="text-align:center; color:red;">No Result!</h4>
                     

                        <hr>
                        </div>
                      </div>';
                  }
                 

                  ?>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
               
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php include 'plugins/footer.php';?>