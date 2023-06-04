<?php 
$title =isset($_GET['genre']) ? urldecode($_GET['genre'])." Movie List": "";
if(isset($_GET['search'])){
    $title = "Look for Movies that has '{$_GET['search']}' keyword...";
}
?>
<style>
    .img-top{
        width:100%;
        height:45vh;
    }
    .movie-cover{
        width:100%;
        height:100%;
        object-fit:scale-down;
        object-position:center center;
        transition:transform .2s ease-in;
    }
    .movie-item:focus .movie-cover,.movie-item:hover .movie-cover{
        transform:scale(1.2);
    }
</style>
<div class="content py-3">
    <h3><b><?= $title ?></b></h3>
    <hr>
    <div class="container-fluid">
        <div class="row row-cols-sm-1 row-cols-md-2 row-cols-xl-4 gx-2 gy-2" id="movie-list">
            <?php 
            $where ="";
            if(isset($_GET['gid'])){
                $where = " where CONCAT('|',REPLACE(`genres`,',','|,|'),'|') LIKE '%|{$_GET['gid']}|%' ";
            }
            if(isset($_GET['search'])){
                $s = $conn->real_escape_string($_GET['search']);
                $where = " where title LIKE '%{$s}%' OR  description  LIKE '%{$s}%' OR  actors LIKE '%{$s}%' OR  director LIKE '%{$s}%' OR writter LIKE '%{$s}%' OR  produced LIKE '%{$s}%' ";
            }
            $movie_query = $conn->query("SELECT * FROM `movie_list` {$where} order by `title` asc");
            while($row = $movie_query->fetch_assoc()):
            ?>
            <div class="col">
                <a href="./?page=movie_details&id=<?= $row['id'] ?>" class="card card-outline card-primary shadow rounded-0 movie-item text-decoration-none text-dark">
                    <div class="img-top overflow-hidden" >
                        <img src="<?= validate_image($row['image_path']) ?>" alt="<?= $row['title'] ?>" class="movie-cover bg-gradient-dark img-fluid">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title w-100 text-center"><b><?= $row['title'] ?></b></h5>
                        <center>(<?= date("Y",strtotime($row['release_date'])) ?>)</center>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        <?php if($movie_query->num_rows <= 0): ?>
            <?php if(isset($_GET['gid'])): ?>
                <center><small class="text-muted"><em>Genre has no listed movie.</em></small></center>
            <?php endif; ?>
            <?php if(isset($_GET['search'])): ?>
                <center><small class="text-muted"><em>No search result.</em></small></center>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>