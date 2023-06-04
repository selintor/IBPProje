<?php 
$user = $conn->query("SELECT *,CONCAT(lastname,', ',firstname,' ',middlename) as fullname FROM client_list where id ='{$_settings->userdata('id')}'");
foreach($user->fetch_array() as $k =>$v){
    $$k = $v;
}
?>
<style>
    .client-img{
		object-fit:scale-down;
		object-position:center center;
        height:200px;
        width:200px;
	}
</style>
<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title">Your Information:</h5>
            <div class="card-tools">
                <a href="./?page=manage_account" class="btn btn-default bg-navy btn-flat"><i class="fa fa-edit"></i> Update Account</a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <center>
                                <img src="<?= validate_image($avatar) ?>" alt="client Image" class="img-fluid client-img bg-gradient-dark border">
                            </center>
                        </div>
                        <div class="col-lg-8 col-sm-12">
                            <dl>
                                <dt class="text-navy">Name:</dt>
                                <dd class="pl-4"><?= ucwords($fullname) ?></dd>
                                <dt class="text-navy">Gender:</dt>
                                <dd class="pl-4"><?= ucwords($gender) ?></dd>
                                <dt class="text-navy">Email:</dt>
                                <dd class="pl-4"><?= $email ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>