<style>
    .car-cover{
        width:10em;
    }
    .car-item .col-auto{
        max-width: calc(100% - 12em) !important;
    }
    .car-item:hover{
        transform:translate(0, -4px);
        background:#a5a5a521;
    }
    .banner-img-holder{
        height:25vh !important;
        width: calc(100%);
        overflow: hidden;
    }
    .banner-img{
        object-fit:scale-down;
        height: calc(100%);
        width: calc(100%);
        transition:transform .3s ease-in;
    }
    .car-item:hover .banner-img{
        transform:scale(1.3)
    }
</style>
<div class="card card-outline card-primary shadow bg-gradient2">
    <div class="card-body">
        <div class="container-fluid">
            <h3 class="text-center">About Us</h3>
            <hr>
            <?php include("about_us.html") ?>
        </div>
    </div>
</div>