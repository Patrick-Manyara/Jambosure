<?php
$page        = 'test';
$header_name = 'Home';

//  require_once '../path.php';
require_once 'header.php';

$cats       = get_dropdown_data(get_all('category'), 'category_name', 'category_id');
$subcats    = get_dropdown_data(get_all('subcategory'), 'subcategory_name', 'subcategory_id');
?>


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="col-lg-12 mb-4">
                    <form enctype="multipart/form-data" action="benefits.php" method="POST">
                        <?php
                        input_hybrid('Vehicle Make', 'make', $row, true);
                        input_hybrid('Vehicle Model', 'model', $row, true);
                        input_hybrid('Year of Manufacture', 'year', $row, true, "number");
                        input_hybrid('Car Value', 'value', $row, true, "number");
                        input_select_array("Comprehensive or Third Party?", "category_id", $row, true, $cats, 'Click Here');
                        ?>
 
                        <div id="subcat">
                            <?php
                            input_select_array("Vehicle Use", "subcategory_id", $row, false, $subcats, 'Click Here');
                            ?>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4 text-center">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>


    </div>


</div>



<style>
    #subcat {
        display: none;
    }

    .form-group {
        margin-top: 10px;
    }
</style>

<script>
    $(document).ready(function() {
        var SelectedValue;
        $('#category_id').change(function() {
            SelectedValue = this.value;
            console.log(SelectedValue)
            if (SelectedValue == 'CAT20240205ghdSw0f') {
                $('#subcat').css('display', 'none');
            } else {
                $('#subcat').css('display', 'block');
            }
        });

    });
</script>



<?php include_once 'footer.php'; ?>