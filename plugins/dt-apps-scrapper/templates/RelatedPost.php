<?php
global $wpdb;
$table_app = $this->table_app_info;
$table_app_post = $this->table_app_post;

$results = $wpdb->get_results(
    "
  SELECT {$table_app}.*
  FROM {$table_app}
  WHERE EXISTS (
    SELECT 1
    FROM {$table_app_post}
    WHERE {$table_app_post}.app_id = {$table_app}.id
  )
  "
);


?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 pt-5">
            <h1>Related Post</h1>
        </div>
        <div class="card mt-5  p-0">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleFormControlSelect2">Apps</label>

                    <select  class="form-control select2" id="appSelect">
                        <option value="" disabled selected>Select App</option>
                        <?php if (isset($results) && count($results) > 0){
                            foreach ($results as $result){
                            ?>
                        <option value="<?php echo $result->id ?>"><?php echo $result->name ?></option>
                        <?php }}?>
                    </select>
                </div>
                <table id="postTable" class="ui celled table mt-5" style="max-width: 100%">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Post</th>
                        <th>language_code</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>