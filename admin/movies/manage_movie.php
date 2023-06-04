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
        $genre_arr = [];
        if(isset($genres) && !empty($genres))
        $genre_arr = explode(",",$genres);
    }
}
?>
<style>
	img#cimg{
		height: 45vh;
		width: 20vw;
		object-fit: scale-down;
		object-position: center center;
	}
</style>
<div class="content py-3">
    <div class="container-fluid">
        <div class="card card-outline card-info rounded-0 shadow">
            <div class="card-header rounded-0">
                <h4 class="card-title"><?= isset($code) ? "Update Movie Details" : "Add New Movie" ?></h4>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <form action="" id="movie-form">
                        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <input type="text" id="title" name="title" autofocus class="form-control form-control-sm form-control-border" placeholder="Enter Title" required value="<?= isset($title) ? $title : "" ?>">
                                    <small class="text-muted px-4">Movie/Film Title</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8">
                                   <select name="genre_arr[]" id="genre" class="form-control form-control-sm form-control-border select2" multiple data-placeholder="Please Select Genre Here">
                                       <?php 
                                       $genres = $conn->query("SELECT * FROM `genre_list` order by `name` asc");
                                       while($row = $genres->fetch_assoc()):
                                       ?>
                                       <option value="<?= $row['id'] ?>" <?= isset($genre_arr) && in_array($row['id'],$genre_arr) ? "selected" : "" ?>><?= $row['name'] ?></option>
                                       <?php endwhile; ?>
                                   </select>
                                    <small class="text-muted px-4">Movie/Film Title</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Actors <em>(seperate names using comma ' , ')</em></small>
                                    <textarea name="actors" id="actors" rows="3" style="resize:none" class="form-control form-control-sm rounded-0" placeholder=""><?= isset($actors) ? $actors : "" ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Director <em>(seperate names using comma ' , ')</em></small>
                                    <textarea name="director" id="director" rows="3" style="resize:none" class="form-control form-control-sm rounded-0" placeholder=""><?= isset($director) ? $director : "" ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Writter <em>(seperate names using comma ' , ')</em></small>
                                    <textarea name="writter" id="writter" rows="3" style="resize:none" class="form-control form-control-sm rounded-0" placeholder=""><?= isset($writter) ? $writter : "" ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Producer <em>(seperate names using comma ' , ')</em></small>
                                    <textarea name="produced" id="produced" rows="3" style="resize:none" class="form-control form-control-sm rounded-0" placeholder=""><?= isset($produced) ? $produced : "" ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <input type="date" id="release_date" name="release_date" class="form-control form-control-sm form-control-border" placeholder="" required value="<?= isset($release_date) ? $release_date : "" ?>">
                                    <small class="text-muted px-4">Release Date</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Description</small>
                                    <textarea name="description" id="description" rows="5" style="resize:none" class="form-control form-control-sm rounded-0" placeholder=""><?= isset($description) ? $description : "" ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <input type="file" name="img" class="form-control form-control-border" id="img" onchange="displayImg(this,$(this))">
                                </div>
                                <div class="form-group col-md-4">
                                    <center>
                                    <img src="<?php echo validate_image(isset($image_path) ? $image_path : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail bg-gradient-dark">
                                    </center>
                                </div>
                            </div>
                        </fieldset>
                        
                        <hr class="bg-navy">
                        <center>
                            <button class="btn btn-sm bg-primary btn-flat mx-2 col-3">Save</button>
                            <?php if(isset($id)): ?>
                                <a class="btn btn-sm btn-light border btn-flat mx-2 col-3" href="./?page=movies/view_details&id=<?= $id ?>">Cancel</a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-light border btn-flat mx-2 col-3" href="./?page=movies">Cancel</a>
                            <?php endif; ?>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
	        	$('#cimg').attr('src', "<?php echo validate_image(isset($image_path) ? $image_path : '') ?>");
        }
	}
    function submit_movie(){
        var _this = $("#movie-form")
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_movie",
				data: new FormData($("#movie-form")[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href="./?page=movies/view_details&id="+resp.id;
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html, body').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
    }
    $(function(){
        $('.select2').each(function(){
            var _this = $(this)
            _this.select2({
                placeholder:_this.attr('data-placeholder') || 'Please Select Here',
                width:'100%'
            })
        })
        $('#movie-form').submit(function(e){
            e.preventDefault()
            _conf("Please make sure that you have reviewed the form before you continue to save the movie details.","submit_movie",[])
            
        })
    })
</script>