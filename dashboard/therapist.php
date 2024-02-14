<?php
$page        = 'therapist';

//  require_once '../path.php';
require_once 'header.php';

$current_year   = date("Y");

$therapist = get_by_id('therapist', security('id', 'GET'));

if (!empty($therapist)) {
    session_assignment(array(
        'edit' => $therapist['therapist_id']
    ), false);
    $require = false;
} else {
    $require = true;
}

// $categories     = get_dropdown_data(get_all('category'), 'category_name', 'category_id');

// $category_name =  get_by_id('category', $therapist['category_id']);
$alphanumericArray = explode('|', $therapist['category_id']);

$cats = array();
foreach ($alphanumericArray as $value){
    $cats[] =  get_by_id('category', $value)['category_name'];
}

$cats = implode(",", $cats);
// cout($cats);
$all_cats = get_all('category');
?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Therapists </h3>
                </div>
                <div class="mt-4">
                    <form enctype="multipart/form-data" action="<?= model_url ?>therapist" method="POST">
                        <div class="row clearfix">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php
                                input_hybrid('Name', 'therapist_name', $therapist, $require);
                                ?>
                                <div class="form-group">
                                    <label for='email'>Email</label>
                                    <div class="input-group">
                                        <input autocomplete="on" type="text" class="form-control" name="therapist_email" id="email" placeholder="Email" value="<?php echo isset($therapist['therapist_email']) ? $therapist['therapist_email'] : ''; ?>" onBlur="checkAvailabilityEmailid()" required>
                                    </div>
                                    <span id="emailid-availability" style="font-size:12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="category"><?= ucfirst('Category ') ?> : <?= !empty($therapist) ? $cats : '' ?> </label>
                                    <select id="exampleFormControlSelect2" multiple data-placeholder="Select a category" class="select2 form-control" name="category_id[]">

                                        <?php foreach ($all_cats as $value) {
                                            $category_id = $value['category_id'];
                                        ?>
                                            <option value="<?= $category_id ?>"><?= ucwords($value['category_name']) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <?php
                                input_hybrid('Phone Number', 'therapist_phone', $therapist, $require);

                                // input_select_array("Category?", "category_id", $therapist, false, $categories);

                                input_hybrid('Location', 'therapist_location', $therapist, $require);
                                input_hybrid('address', 'therapist_address', $therapist, $require);
                                input_hybrid('password', 'therapist_password', $therapist, $require);
                                input_hybrid('gender', 'therapist_gender', $therapist, $require);
                                input_hybrid('Registration/ license number', 'therapist_license', $therapist, $require);
                                input_hybrid('Area of speciality', 'therapist_specialty', $therapist, $require);

                                input_hybrid('Years of experience', 'therapist_experience', $therapist, $require, 'number');
                                textarea_input('statement quote', 'therapist_statement', $therapist, $require);
                                textarea_input('qualifications', 'therapist_qualifications', $therapist, $require);
                                textarea_input('bio', 'therapist_bio', $therapist, $require);

                                if (!empty($therapist['therapist_image'])) :
                                    $require = false;
                                    $image = $therapist['therapist_image'];
                                else :
                                    $require = true;
                                    $image = 'white_bg_image.png';
                                endif;
                                input_hybrid("therapist Image", "therapist_image", $therapist, $require, "file", 'my_img', '', 'img');
                                ?>
                                <img alt="image" src="<?= file_url . $image ?>" id="img_loader" style="border-radius: 5%; border-color:grey; border-style: solid; height:auto; width: 60%;">

                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4 text-center">
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" id="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Main Content -->

    <script>
        function checkAvailabilityEmailid() {
            jQuery.ajax({
                url: "../check_available.php",
                data: 'therapist_email=' + $("#email").val(),
                type: "POST",
                success: function(data) {
                    $("#emailid-availability").html(data);
                },
                error: function() {}
            });
        }
    </script>

    <?php include_once 'footer.php'; ?>