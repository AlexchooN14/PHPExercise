<?php 
    require_once('header.php');
    require_once("../functions.php");
    session_regenerate_id();
?>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <div class="my-1">
            <img src="<?php echo getProfileImage($_SESSION['user_id']) ?>" class="profile-image" style="max-width: auto;max-height: 50px;border-radius: 50%;" alt="">
        </div>
        <label for="formFile" class="form-label">Change your profile picture</label>
        <input class="form-control" type="file" id="formFile" name="profile-picture" max-size="2000000">
    </div>
    <button class="btn btn-dark" type="submit" name="submit-profile">Submit</button>
</form>

<br>
    <h3>or</h3>
<br>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="formFileMultiple" class="form-label">Add pictures to your gallery</label>
        <input class="form-control" type="file" id="formFileMultiple" name="gallery_images[]" multiple>
    </div>
    <button class="btn btn-dark mb-2" type="submit" name="submit-gallery">Submit</button>
</form>

<br>
    <h3>or</h3>
<br>

<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="formFileMultiple" class="form-label">Add pictures to your gallery</label>
        <input class="form-control" type="file" id="formFileMultiple" name="files[]" multiple>
    </div>
    <button class="btn btn-dark mb-2" type="submit" name="submit-files">Submit</button>
</form>
<br>

<?php
    if (isset($_POST['submit-profile'])) {
        
        $filename = $_FILES['profile-picture']['name'];
        $tempname = $_FILES['profile-picture']['tmp_name'];
        $filesize = $_FILES['profile-picture']['size'];

        setProfileImage($_SESSION['user_id'], $filename, $tempname, $filesize);
    }
    if (isset($_FILES['gallery_images'])) {
        $filenames = $_FILES['gallery_images']['name'];
        $tempnames = $_FILES['gallery_images']['tmp_name'];
        $filesizes = $_FILES['gallery_images']['size'];
        addFiles($_SESSION['user_id'], $filenames, $tempnames, $filesizes, FileTypes::Image);
        // addGalleryImages($_SESSION['user_id'], $filenames, $tempnames, $filesizes);
    }
    if (isset($_FILES['files'])) {
        $filenames = $_FILES['files']['name'];
        $tempnames = $_FILES['files']['tmp_name'];
        $filesizes = $_FILES['files']['size'];        
        addFiles($_SESSION['user_id'], $filenames, $tempnames, $filesizes, FileTypes::File);
    }
?>


<!-- Footer file -->
<?php require_once('footer.php'); ?>