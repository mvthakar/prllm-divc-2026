<?php require_once '../@/lib/init.php'; ?>
<?php
require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');

$db = Database::getInstance();
$search = trim(get('search') ?? '');

if ($search !== '') {
  $categories = $db->getAll(
    "SELECT `id`, `name` FROM `categories` WHERE `deleted_at` IS NULL AND `name` LIKE ? ORDER BY `name`",
    ['%' . $search . '%']
  );
} else {
  $categories = $db->getAll("SELECT `id`, `name` FROM `categories` WHERE `deleted_at` IS NULL ORDER BY `name`");
}

$pageTitle = 'Categories';
?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="row mb-2">
  <div class="col-sm-6"></div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item active">Categories</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col text-right">
    <a role="button" class="btn btn-success" href="<?= urlOf('/categories/add.php') ?>">Add New</a>
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
              <th scope="col" class="item-list-actions" style="width: 20%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($categories)) : ?>
            <tr>
              <td colspan="2" class="text-center text-muted">No categories found.</td>
            </tr>
            <?php endif; ?>
            <?php foreach ($categories as $category): ?>
            <tr>
              <td class="item-list-name"><?= htmlspecialchars($category->name) ?></td>
              <td class="item-list-actions">
                <div class="btn-group" role="group" aria-label="Actions">
                  <a role="button" class="btn btn-warning" href="<?= urlOf('/categories/edit.php?id=' . urlencode($category->id)) ?>" data-toggle="tooltip" data-placement="bottom" title="Edit">
                    <i class="far fa-edit"></i>
                  </a>
                  <a role="button" class="btn btn-danger btn-delete" href="<?= urlOf('/categories/delete.php?id=' . urlencode($category->id)) ?>" data-toggle="tooltip" data-placement="bottom" title="Delete">
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