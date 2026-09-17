<?php require_once '../@/lib/init.php'; ?>
<?php

require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');
require_once pathOf('/@/lib/response.php');

$error = "";
$name = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim(post('name') ?? '');

  if ($name === '') {
    $error = "Name cannot be empty";
  } else {
    $db = Database::getInstance();
    $existing = $db->getOne(
      "SELECT COUNT(*) AS `count` FROM `categories` WHERE `name` = ? AND `deleted_at` IS NULL",
      [$name]
    );

    if ($existing->count > 0) {
      $error = "Category with this name already exists";
    } else {
      $db->execute("INSERT INTO `categories` (`name`) VALUES (?)", [$name]);
      exitWithRedirect('/categories');
    }
  }
}

$pageTitle = 'Add Category';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?= urlOf('/categories') ?>">Categories</a></li>
      <li class="breadcrumb-item active">Add</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col">
    <form method="post" action="<?= urlOf('/categories/add.php') ?>">
      <div class="card card-outline card-info">
        <div class="card-body">
          <div class="row mt-3">
            <div class="col">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?= htmlspecialchars($name) ?>" required autofocus>
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