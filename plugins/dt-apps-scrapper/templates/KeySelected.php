
<div class="container">
    <div class="row">
        <div class="col-lg-12 pt-5">
          <h1> CustomFields </h1>
        </div>
        <div class="card mt-5  p-0">
            <div class="card-body">
            <table class="table">
  <thead>
    
    <tr>
      <th scope="col">#</th>
      <th scope="col">key </th>
      <th scope="col">value</th>

      <th scope="col">status</th>
    </tr>
  </thead>
  <tbody>
<?php
  global $wpdb;
       $app_id=$_GET["app_id"];
         $table_dt_meta =  $this->table_dt_meta;;
        $results = $wpdb->get_results("SELECT * FROM $table_dt_meta where app_id='$app_id'");
    ?>
        <?php if (isset($results) && count($results)>0){
    foreach ($results as $row) { 
        if ($row->status == 0){
         $status_button= '<button type="button" id="status_button_'.$row->id.'" onclick="connect_the_key('.$row->id.',1)"  class="btn btn-primary"> Checked</button>';
        }else{
            $status_button= '<button type="button" id="status_button_danger_'.$row->id.'" onclick="connect_the_key('.$row->id.',0)"  class="btn btn-danger">UnChecked </button>';
        }
        ?>
    <tr>

      <td scope="row"><?php echo $row->id  ?></td>
      <td><?php echo $row->key  ?></td>
      <td><?php echo $row->value  ?></td>
      <td><?php echo $status_button ?></td>

            </tr>
    <?php }}else{?>

            <tr>
        <td colspan="4" class="text-center"><h3>no result</h3> </td>
            </tr>
       <?php }?>
        </tbody>
        </table>
            </div>
        </div>
    </div>
</div>