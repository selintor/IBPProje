<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `movie_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
        $genre_qry = $conn->query("SELECT * FROM genre_list where id in ({$genres})");
        $genre_arr = [];
        if($genre_qry->num_rows > 0)
        $genre_arr = array_column($genre_qry->fetch_all(MYSQLI_ASSOC),'name');
        $genre_names = count($genre_arr) > 0 ? implode(", ",$genre_arr) : "N/A";
        $description = str_replace("\n","<br>",$description);
    }else{
    echo "<script>alert('Unknown Movie ID'); location.replace('./?page=movies');</script>";
    }
}
else{
    echo "<script>alert('Movie ID is required'); location.replace('./?page=movies');</script>";
}
?>
<style>
    @media screen {
        .show-print{
            display:none;
        }
    }
    img#movie-banner{
		height: 45vh;
		width: 20vw;
		object-fit: scale-down;
		object-position: center center;
	}
    .table.border-info tr, .table.border-info th, .table.border-info td{
        border-color:var(--info);
    }
</style>
<?php 
$success_rate = 0;
$success_perc = 0;
$count_reviews = $conn->query("SELECT id from review_list where movie_id ='{$id}'")->num_rows;
$overall_rating = $conn->query("SELECT SUM(rating) as total from review_list where movie_id ='{$id}'")->fetch_array()[0];
if($count_reviews  > 0){
    $success_rate = round($overall_rating / $count_reviews,2);
    $success_perc = number_format(($overall_rating / ($count_reviews * 5)) * 100);
}

?>
<div class="content py-3">
    <div class="card card-outline card-dark rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title text-primary">Movie Details</h5>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div id="outprint">
                    <fieldset>
                        <div class="row justify-content-center bg-gradiend-dark">
                            <div class="col-auto">
                                <img src="<?= validate_image($image_path) ?>" alt="Movie Cover" id="movie-banner">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-navy text-center"><b><?= $title ?></b></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered border-info">
                                    <colgroup>
                                        <col width="30%">
                                        <col width="70%">
                                    </colgroup>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Directed By</th>
                                        <td><?= ucwords($director) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Written By</th>
                                        <td><?= ucwords($writter) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Actors</th>
                                        <td><?= ucwords($actors) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Produced By</th>
                                        <td><?= ucwords($produced) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Release Date</th>
                                        <td><?= date("F d, Y",strtotime($release_date)) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Genre</th>
                                        <td><?= $genre_names ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted text-white bg-gradient-info px-2 py-1">Predicted Success Rate:</th>
                                        <td> 
                                            <?php if($count_reviews  > 0): ?>
                                            <b><?= $success_rate ?>/5</b> <span class="text-muted">or</span> <b><?= $success_perc."%" ?></b>
                                            <?php else: ?>
                                                <span class="text-muted">No Reviews Listed yet</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                                <h3 class="text-muted"><b>Description</b></h3>
                                <hr>
                                <p><?= $description ?></p>
                            </div>
                        </div>
                        
                    </fieldset>
                </div>
                <hr>
                <div class="rounded-0 mt-3">
                    <h3 class="text-muted"><b>Review/s:</b></h3>
                    <?php 
                    $reviews = $conn->query("SELECT r.*,c.email,c.avatar FROM review_list r inner join client_list c on r.client_id = c.id where r.movie_id = '{$id}' order by unix_timestamp(r.`date_created`) asc ");
                    ?>
                    <div id="review_list">
                        <?php while($row=$reviews->fetch_assoc()): ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lx-6">
                                <div class="card card-outline card-primary shadow rounded-0">
                                    <div class="card-header py-1 rounded-0">
                                        <div class="row">
                                            <div class="col-1">
                                                <img src="<?= validate_image($row['avatar']) ?>" alt="User Avatar" class="review-user-img">
                                            </div>
                                            <div class="col-9" style="line-height: .5em;">
                                                <h4 class="card-title w-100"><b><?= $row['title'] ?></b></h4>
                                                <small><span class="text-primary"><?= $row['email'] ?></span> <span class="mx-3"><?= date("Y-m-d H:i", strtotime($date_created)) ?></span></small>
                                            </div>
                                            <div class="col-2">
                                                <h2 class="text-muted text-right"><?= $row['rating'] ?>/5</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body rounded-0">
                                        <p><?= $row['comment'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php if($reviews->num_rows <=0): ?>
                        <small class="tex-muted"><em>No Review Listed yet.</em></small>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="rounded-0 text-center mt-3">
                        <a class="btn btn-sm btn-primary btn-flat" href="./?page=movies/manage_movie&id=<?= $id ?>"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-sm btn-danger btn-flat" type="button" id="delete_data"><i class="fa fa-trash"></i> Delete</button>
                        <a class="btn btn-light border btn-flat btn-sm" href="./?page=movies" ><i class="fa fa-angle-left"></i> Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#delete_data').click(function(){
			_conf("Are you sure to delete <b><?= $code ?>\'s</b> from movie permanently?","delete_movie",[$(this).attr('data-id')])
		})
    })
    function delete_movie($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_movie",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace= './?page=movies';
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>