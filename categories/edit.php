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

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim(post('name') ?? '');

  if ($name === '') {
    $error = "Name cannot be empty";
  } else {
    $existing = $db->getOne(
      "SELECT COUNT(*) AS `count` FROM `categories` WHERE `name` = ? AND `deleted_at` IS NULL AND `id` != ?",
      [$name, $id]
    );

    if ($existing->count > 0) {
      $error = "Category with this name already exists";
    } else {
      $db->execute("UPDATE `categories` SET `name` = ? WHERE `id` = ?", [$name, $id]);
      exitWithRedirect('/categories');
    }
  }
}

$category = $db->getOne("SELECT `id`, `name` FROM `categories` WHERE `id` = ? AND `deleted_at` IS NULL", [$id]);
if ($category == null) {
  exitWithRedirect('/categories');
}

// keep the field showing what the user typed if validation failed, else the DB value
$name = $name ?? $category->name;

$pageTitle = 'Edit Category';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?= urlOf('/categories') ?>">Categories</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col">
    <form action="<?= urlOf('/categories/edit.php?id=' . urlencode($id)) ?>" method="post">
      <div class="card card-outline card-info">
        <div class="card-body">
          <div class="row mt-3">
            <div class="col">
              <div class="form-group">
                <label for="name">Name</label>
                <input name="name" value="<?= htmlspecialchars($name) ?>" type="text" class="form-control" id="name" placeholder="Enter Name" required autofocus>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success btn-submit">Submit</button>
          <?php if (!empty($error)) : ?>
          <br><br>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?= htmlspecialchars($error) ?></strong>
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

<?php require_once pathOf('/@/includes/scripts.php'); ?>
<?php require_once pathOf('/@/includes/footer.php'); ?>