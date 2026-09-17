<?php require_once '../@/lib/init.php'; ?>
<?php

require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');
require_once pathOf('/@/lib/response.php');

$db = Database::getInstance();
$id = get("id");
if ($id == null) {
  exitWithRedirect('/products');
}

$error = "";
$name = null;
$categoryId = null;
$price = null;
$description = null;

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
        "UPDATE `products` SET `category_id` = ?, `name` = ?, `price` = ?, `description` = ? WHERE `id` = ?",
        [$categoryId, $name, $price, $description === '' ? null : $description, $id]
      );
      exitWithRedirect('/products');
    }
  }
}

$product = $db->getOne(
  "SELECT `id`, `category_id`, `name`, `price`, `description` FROM `products` WHERE `id` = ? AND `deleted_at` IS NULL",
  [$id]
);
if ($product == null) {
  exitWithRedirect('/products');
}

// keep the fields showing what the user typed if validation failed, else the DB values
$name = $name ?? $product->name;
$categoryId = $categoryId ?? $product->category_id;
$price = $price ?? $product->price;
$description = $description ?? $product->description;

$categories = $db->getAll("SELECT `id`, `name` FROM `categories` WHERE `deleted_at` IS NULL ORDER BY `name`");

$pageTitle = 'Edit Product';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="<?= urlOf('/products') ?>">Products</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col">
    <form action="<?= urlOf('/products/edit.php?id=' . urlencode($id)) ?>" method="post">
      <div class="card card-outline card-info">
        <div class="card-body">
          <div class="row mt-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Name</label>
                <input name="name" value="<?= htmlspecialchars($name) ?>" type="text" class="form-control" id="name" placeholder="Enter Name" required autofocus>
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
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter Description"><?= htmlspecialchars($description ?? '') ?></textarea>
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