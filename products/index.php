<?php require_once '../@/lib/init.php'; ?>
<?php
require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');

$db = Database::getInstance();
$search = trim(get('search') ?? '');

if ($search !== '') {
  $products = $db->getAll(
    "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `categories`.`name` AS `category_name`
     FROM `products`
     JOIN `categories` ON `categories`.`id` = `products`.`category_id`
     WHERE `products`.`deleted_at` IS NULL AND `products`.`name` LIKE ?
     ORDER BY `products`.`name`",
    ['%' . $search . '%']
  );
} else {
  $products = $db->getAll(
    "SELECT `products`.`id`, `products`.`name`, `products`.`price`, `categories`.`name` AS `category_name`
     FROM `products`
     JOIN `categories` ON `categories`.`id` = `products`.`category_id`
     WHERE `products`.`deleted_at` IS NULL
     ORDER BY `products`.`name`"
  );
}

$pageTitle = 'Products';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item active">Products</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col text-right">
    <a role="button" class="btn btn-success" href="<?= urlOf('/products/add.php') ?>">Add New</a>
  </div>
</div>
<div class="row">&nbsp;</div>

<div class="card card-outline card-info">
  <div class="card-body">
    <div class="row">
      <div class="col col-lg-4 offset-lg-8 text-right mb-4 col-12">
        <form action="" method="get" class="input-group">
          <input type="search" class="form-control" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
          <div class="input-group-append">
            <button type="submit" class="btn btn-info">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <table class="table table-striped item-list">
          <thead>
            <tr>
              <th scope="col" class="item-list-title">Name</th>
              <th scope="col">Category</th>
              <th scope="col">Price</th>
              <th scope="col" class="item-list-actions" style="width: 20%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($products)) : ?>
            <tr>
              <td colspan="4" class="text-center text-muted">No products found.</td>
            </tr>
            <?php endif; ?>
            <?php foreach ($products as $product): ?>
            <tr>
              <td class="item-list-name"><?= htmlspecialchars($product->name) ?></td>
              <td><?= htmlspecialchars($product->category_name) ?></td>
              <td><?= number_format((float) $product->price, 2) ?></td>
              <td class="item-list-actions">
                <div class="btn-group" role="group" aria-label="Actions">
                  <a role="button" class="btn btn-warning" href="<?= urlOf('/products/edit.php?id=' . urlencode($product->id)) ?>" data-toggle="tooltip" data-placement="bottom" title="Edit">
                    <i class="far fa-edit"></i>
                  </a>
                  <a role="button" class="btn btn-danger btn-delete" href="<?= urlOf('/products/delete.php?id=' . urlencode($product->id)) ?>" data-toggle="tooltip" data-placement="bottom" title="Delete">
                    <i class="far fa-trash-alt"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once pathOf('/@/includes/scripts.php'); ?>
<?php require_once pathOf('/@/includes/footer.php'); ?>