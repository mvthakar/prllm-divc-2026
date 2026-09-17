<?php require_once '../@/lib/init.php'; ?>
<?php

require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');
require_once pathOf('/@/lib/response.php');

$db = Database::getInstance();
$id = get("id");
if ($id == null) {
  exitWithRedirect('/categories');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = post('name');
  $error = "";

  if (empty($name)) {
    $error = "Name cannot be empty";
  }

  $existing = $db->getOne("SELECT COUNT(*) AS `count` FROM `categories` WHERE `name` = ? AND `deleted_at` IS NULL AND `id` != ?", [$name, $id]);

  if ($existing->count > 0) {
    $error = "Category with this name already exists";
  } else {
    $db->execute("UPDATE `categories` SET `name` = ? WHERE `id` = ?", [$name, $id]);
    exitWithRedirect('/categories');
  } 

}
$category = $db->getOne("SELECT `id`, `name` FROM `categories` WHERE `id` = ?", [$id]);
if ($category == null) {
  exitWithRedirect('/categories');
}

?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="content-wrapper">
  <div class="container">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">&nbsp;</div>
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit App</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= urlOf('/') ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?= urlOf('/categories') ?>">Categories</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div>
        </div>
        <div class="row">&nbsp;</div>
      </div>
    </section>
    <section class="content">
      <div class="row">
        <div class="col">
          <form action="<?= urlOf('/categories/edit.php?id=' . $id) ?>" method="post">
            <div class="card card-outline card-info">
              <div class="card-body">
                <div class="row mt-3">
                  <div class="col">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input name="name" value="<?= $category->name ?>" type="text" class="form-control" id="name" placeholder="Enter Name" required autofocus>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-success btn-submit">Submit</button>
                <?php if (!empty($error)) : ?>
                <br><br>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong><?= $error ?></strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</div>

<?php require_once pathOf('/@/includes/scripts.php'); ?>
<?php require_once pathOf('/@/includes/footer.php'); ?>