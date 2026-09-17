<?php require_once '../@/lib/init.php'; ?>
<?php

require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');
require_once pathOf('/@/lib/response.php');

$db = Database::getInstance();

$error = "";
$name = "";
$categoryId = "";
$price = "";
$description = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim(post('name') ?? '');
  $categoryId = trim(post('category_id') ?? '');
  $price = trim(post('price') ?? '');
  $description = trim(post('description') ?? '');

  if ($name === '') {
    $error = "Name cannot be empty";
  } elseif ($categoryId === '') {
    $error = "Category must be selected";
  } elseif ($price === '' || !is_numeric($price) || (float) $price < 0) {
    $error = "Price must be a valid, non-negative number";
  } else {
    $category = $db->getOne(
      "SELECT `id` FROM `categories` WHERE `id` = ? AND `deleted_at` IS NULL",
      [$categoryId]
    );

    if ($category == null) {
      $error = "Selected category does not exist";
    } else {
      $db->execute(
        "INSERT INTO `products` (`category_id`, `name`, `price`, `description`) VALUES (?, ?, ?, ?)",
        [$categoryId, $name, $price, $description === '' ? null : $description]
      );
      exitWithRedirect('/products');
    }
  }
}

$categories = $db->getAll("SELECT `id`, `name` FROM `categories` WHERE `deleted_at` IS NULL ORDER BY `name`");

$pageTitle = 'Add Product';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?= urlOf('/products') ?>">Products</a></li>
      <li class="breadcrumb-item active">Add</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col">
    <form method="post" action="<?= urlOf('/products/add.php') ?>">
      <div class="card card-outline card-info">
        <div class="card-body">
          <div class="row mt-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?= htmlspecialchars($name) ?>" required autofocus>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $category): ?>
                  <option value="<?= htmlspecialchars($category->id) ?>" <?= (string) $categoryId === (string) $category->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category->name) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?= htmlspecialchars($price) ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter Description"><?= htmlspecialchars($description) ?></textarea>
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