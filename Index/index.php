<!-- Header file -->
<?php 
    require_once('header.php');
    require_once("../functions.php");
?>
<?php if (isset($_SESSION["user_id"])): ?>
    <br>
    <?php $profile_image = getProfileImage($_SESSION['user_id']); ?>
    <img src="<?php echo $profile_image ?>" class='profile-image float-start' style='max-width: auto;max-height: 50px;border-radius: 50%;' alt="">
    <h2>Hello <?php echo htmlspecialchars(getUserById($_SESSION["user_id"])['name']) ?></h2>

    <?php $gallery = getUserGallery($_SESSION["user_id"]);?>
    <br>
    <?php if (!empty($gallery)): ?>
        <h4>This is your gallery!</h4>
        <div id="ImageCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($gallery as $key=>$image):?>
                    <?php if ($key==0): ?>
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="<?php echo "..".IMAGE_FOLDER.$image ?>" alt="">
                        </div>
                    <?php else: ?>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="<?php echo "..".IMAGE_FOLDER.$image ?>" alt="">
                        </div>                      
                    <?php endif; ?>                        
                <?php endforeach;?>                    
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#ImageCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#ImageCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <?php else: ?>
        <h4>You don't currently have anything in your gallery!</h4>
    <?php endif; ?>  

<?php else: ?>
    <h2>Hello! Log In or Register</h2>
<?php endif; ?>


<?php if (isset($_SESSION["user_id"])): ?>
    <br>
    <?php $library = getUserLibrary($_SESSION["user_id"]);?>

    <?php if (!empty($library)): ?>
        <h4>This is your library!</h4>
        <br>
        <div id="FileCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($library as $key=>$file):?>
                    <?php if ($key==0): ?>
                        <div class="carousel-item active">
                            <embed class="d-block w-100" src="<?php echo getSaveDir($file, FileTypes::File) ?>"  alt=""/>
                        </div>
                    <?php else: ?>
                        <div class="carousel-item">
                            <embed class="d-block w-100" src="<?php echo getSaveDir($file, FileTypes::File) ?>"  alt=""/>
                        </div>                      
                    <?php endif; ?>                        
                <?php endforeach;?>                  
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#FileCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon text-dark bg-dark" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#FileCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon text-dark bg-dark " aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <?php else: ?>
        <h4>You don't currently have anything in your library!</h4>
    <?php endif; ?>  

<?php endif; ?>
<br>

<!-- Footer file -->
<?php require_once('footer.php'); ?>
    
