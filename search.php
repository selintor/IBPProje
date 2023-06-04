<div class="content py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header rounded-0">
                        <h4 class="card-title">Search Movie</h4>
                </div>
                <div class="card-body">
                    <form action="" id="search_movie">
                        <div class="form-group">
                            <label for="search" class="control-label text-navy">Search Keyword</label>
                            <input type="text" class="form-control form-control-border" name="search" required>
                        </div>
                        <div class="form-group mt-3 text-center">
                            <button class="btn btn-primary col-4"><i class="fa fa-search"></i> Search Movie</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#search_movie').submit(function(e){
            e.preventDefault()
            location.href="./?page=movies&"+$(this).serialize();
        })
    })
</script>