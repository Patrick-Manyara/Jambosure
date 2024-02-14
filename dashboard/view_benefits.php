<?php
$page = 'benefit';
include_once 'header.php';
$benefits = get_all('benefit');

$num_columns = 11;

$column_indexes = range(0, $num_columns - 1);

// Create an array of column definition objects
$column_defs = array();
for ($i = 0; $i < $num_columns; $i++) {
    $column_defs = array(
        array('data' => '', 'title' => 'id'),
        array('data' => 'col_' . $i, 'title' => 'id'),
        array('data' => 'benefit_phone', 'title' => 'Underwriter'),
        array('data' => 'benefit_image', 'title' => 'Product'),
        array('data' => 'benefit_name', 'title' => 'Category'),
        array('data' => 'benefit_email', 'title' => 'Benefit'),
        array('data' => 'a', 'title' => 'Cost'),
        array('data' => 'b', 'title' => 'Type'),
        array('data' => 'v', 'title' => 'Date Added'),
        array('data' => 'd', 'title' => 'Benefit ID'),
        array('data' => 'aa', 'title' => 'Action')
    );
}
?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">View</span> benefits</h4>
    <a href="benefit">Add</a>
    <!-- DataTable with Buttons -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-basic table border-top">
                <thead>
                    <tr>
                        <th> </th>

                        <th>id</th>
                        <th>Underwriter</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Benefit</th>
                        <th>Cost</th>
                        <th>Type</th>
                        <th>Date Added</th>
                        <th>Benefit ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cnt = 1;
                    foreach ($benefits as $benefit) {
                        $benefit_id = encrypt($benefit['benefit_id']);
                        $product = get_by_id('product', $benefit['product_id']);
                        $category = get_by_id('category', $product['category_id']);
                        $underwriter = get_by_id('writer', $product['writer_id']);

                        if ($benefit['benefit_mode'] == 'rate') {
                            $price = $benefit['benefit_rate'] .  "% of Premium";
                        } else {
                            $price = 'Ksh. ' . $benefit['benefit_price'];
                        }


                        if ($benefit['benefit_mode'] == 'free') {
                            $type = "Free";
                        } else {
                            $type = "Paid";
                        }
                    ?>
                        <tr>
                            <td> </td>
                            <td><?= $cnt ?></td>
                            <td>
                                <img alt="therapist image" src="<?= file_url . $underwriter['writer_image'] ?>" style="width:100px; height:auto; border-radius:5px;" title="<?= $underwriter['writer_name'] ?>">
                            </td>
                            <td> <?= $product['product_name'] ?> </td>

                            <td> <?= $category['category_name'] ?> </td>
                            <td> <?= $benefit['benefit_name'] ?> </td>
                            <td> <?= $price ?> </td>
                            <td> <?= $type ?> </td>
                            <td> <?= get_ordinal_month_year($benefit['benefit_date_created']) ?> </td>
                            <td> <?= strtoupper($benefit['benefit_id']) ?> </td>
                      
                            <td>
                                <a href="<?= admin_url ?>benefit_details?id=<?= $benefit_id ?>" class="btn btn-info">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <a href="<?= delete_url ?>id=<?= $benefit_id ?>&table=<?= encrypt('benefit') ?>&page=<?= encrypt('view_benefits') ?>&method=benefit" class="btn btn-danger">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                        $cnt++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



</div>
<!-- / Content -->


<?php
include_once 'footer.php';
?>