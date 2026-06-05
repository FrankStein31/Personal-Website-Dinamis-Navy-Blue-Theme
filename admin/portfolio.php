<?php
// portfolio.php - Admin CRUD Projects Portfolio
require_once __DIR__ . '/layout_header.php';

$projects = isset($db_data['portfolio']) ? $db_data['portfolio'] : [];

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Delete Action
if ($action === 'delete' && !empty($edit_id)) {
    $filtered_projects = [];
    $found = false;
    $files_to_delete = [];
    
    foreach ($projects as $proj) {
        if ($proj['id'] === $edit_id) {
            $found = true;
            if (!empty($proj['files']) && is_array($proj['files'])) {
                $files_to_delete = $proj['files'];
            } elseif (!empty($proj['file'])) {
                $files_to_delete = [$proj['file']];
            }
            continue; // Skip the item to delete
        }
        $filtered_projects[] = $proj;
    }
    
    if ($found) {
        $db_data['portfolio'] = $filtered_projects;
        if (save_db_data($db_data)) {
            foreach ($files_to_delete as $file_to_delete) {
                if (!empty($file_to_delete)) {
                    delete_file($file_to_delete);
                }
            }
            $_SESSION['success_msg'] = 'Project deleted successfully!';
        } else {
            $_SESSION['error_msg'] = 'Failed to save changes to the database.';
        }
    } else {
        $_SESSION['error_msg'] = 'Project not found.';
    }
    header("Location: portfolio.php");
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);
    
    if (empty($title)) {
        $_SESSION['error_msg'] = 'Project title is required!';
    } else {
        $upload_error = '';
        $uploaded_files = [];
        
        // Find existing project if editing
        $existing_proj = null;
        if ($action === 'edit' && !empty($edit_id)) {
            foreach ($projects as $p) {
                if ($p['id'] === $edit_id) {
                    $existing_proj = $p;
                    if (!empty($p['files']) && is_array($p['files'])) {
                        $uploaded_files = $p['files'];
                    } elseif (!empty($p['file'])) {
                        $uploaded_files = [$p['file']];
                    }
                    break;
                }
            }
        }
        
        // Handle deletion of selected existing files
        if (isset($_POST['delete_existing_files']) && is_array($_POST['delete_existing_files'])) {
            foreach ($_POST['delete_existing_files'] as $del_f) {
                if (($key = array_search($del_f, $uploaded_files)) !== false) {
                    unset($uploaded_files[$key]);
                    delete_file($del_f);
                }
            }
            $uploaded_files = array_values($uploaded_files); // re-index
        }
        
        // Check and process new file uploads (files[] multiple)
        if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
            $files_count = count($_FILES['files']['name']);
            for ($i = 0; $i < $files_count; $i++) {
                if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                    $single_file = [
                        'name' => $_FILES['files']['name'][$i],
                        'type' => $_FILES['files']['type'][$i],
                        'tmp_name' => $_FILES['files']['tmp_name'][$i],
                        'error' => $_FILES['files']['error'][$i],
                        'size' => $_FILES['files']['size'][$i]
                    ];
                    
                    $upload_res = upload_file($single_file, ['jpg', 'jpeg', 'png', 'pdf']);
                    
                    if ($upload_res['success']) {
                        $uploaded_files[] = $upload_res['filename'];
                    } else {
                        $upload_error = $upload_res['message'];
                        break;
                    }
                } elseif ($_FILES['files']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                    $upload_error = 'Error occurred during file upload.';
                    break;
                }
            }
        }
        
        if (empty($upload_error)) {
            if ($action === 'add') {
                $new_proj = [
                    'id' => uniqid(),
                    'title' => $title,
                    'description' => $description,
                    'link' => $link,
                    'files' => $uploaded_files,
                    'file' => !empty($uploaded_files) ? $uploaded_files[0] : '' // legacy support
                ];
                $db_data['portfolio'][] = $new_proj;
                
                if (save_db_data($db_data)) {
                    $_SESSION['success_msg'] = 'New project added successfully!';
                    header("Location: portfolio.php");
                    exit;
                } else {
                    $_SESSION['error_msg'] = 'Failed to save data to the database.';
                }
            } elseif ($action === 'edit' && !empty($edit_id)) {
                $updated = false;
                foreach ($db_data['portfolio'] as &$proj) {
                    if ($proj['id'] === $edit_id) {
                        $proj['title'] = $title;
                        $proj['description'] = $description;
                        $proj['link'] = $link;
                        $proj['files'] = $uploaded_files;
                        $proj['file'] = !empty($uploaded_files) ? $uploaded_files[0] : '';
                        $updated = true;
                        break;
                    }
                }
                
                if ($updated) {
                    if (save_db_data($db_data)) {
                        $_SESSION['success_msg'] = 'Project updated successfully!';
                        header("Location: portfolio.php");
                        exit;
                    } else {
                        $_SESSION['error_msg'] = 'Failed to save changes to the database.';
                    }
                } else {
                    $_SESSION['error_msg'] = 'Project not found.';
                }
            }
        } else {
            $_SESSION['error_msg'] = $upload_error;
        }
    }
}

// Load data for editing
$edit_proj = null;
if ($action === 'edit' && !empty($edit_id)) {
    foreach ($projects as $p) {
        if ($p['id'] === $edit_id) {
            $edit_proj = $p;
            break;
        }
    }
    if (!$edit_proj) {
        $_SESSION['error_msg'] = 'Project not found.';
        header("Location: portfolio.php");
        exit;
    }
}
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add or Edit Form -->
    <div style="margin-bottom: 25px;">
        <a href="portfolio.php" class="file-link" style="font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-card">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px;">
            <?= ($action === 'add') ? 'Add New Project' : 'Edit Project' ?>
        </h2>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Project Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($edit_proj ? $edit_proj['title'] : '') ?>" placeholder="Enter project title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Project Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Describe the project details, technologies used, or your contribution..." style="min-height: 120px;"><?= htmlspecialchars($edit_proj ? $edit_proj['description'] : '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="link">Project URL / Link</label>
                <input type="url" id="link" name="link" class="form-control" value="<?= htmlspecialchars($edit_proj ? $edit_proj['link'] : '') ?>" placeholder="e.g. https://github.com/... or https://myproject.com">
            </div>
            
            <div class="form-group">
                <label for="files">Project Documents / Images (Multiple files allowed)</label>
                <input type="file" id="files" name="files[]" class="form-control" accept="image/png, image/jpeg, image/jpg, application/pdf" multiple>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">Supported formats: JPG, JPEG, PNG, PDF. You can select multiple files at once.</p>
                
                <?php 
                $proj_files = [];
                if ($edit_proj) {
                    if (!empty($edit_proj['files']) && is_array($edit_proj['files'])) {
                        $proj_files = $edit_proj['files'];
                    } elseif (!empty($edit_proj['file'])) {
                        $proj_files = [$edit_proj['file']];
                    }
                }
                if (!empty($proj_files)): 
                ?>
                    <div style="margin-top: 20px;">
                        <p style="font-size: 0.85rem; font-weight: 600; color: var(--dark); margin-bottom: 10px;">Current Attachments (Check to Delete):</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 15px;">
                            <?php foreach ($proj_files as $f): 
                                $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                            ?>
                                <div style="position: relative; border: 1px solid var(--border); border-radius: 8px; padding: 10px; background-color: var(--primary-light); text-align: center;">
                                    <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                        <img src="../files/<?= htmlspecialchars($f) ?>" style="width: 100%; height: 80px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;">
                                    <?php else: ?>
                                        <div style="height: 80px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--primary); font-size: 1.8rem; margin-bottom: 8px;">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div style="font-size: 0.75rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; color: var(--text-muted); margin-bottom: 10px; padding: 0 4px;" title="<?= htmlspecialchars($f) ?>">
                                        <?= htmlspecialchars($f) ?>
                                    </div>
                                    <label style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; font-size: 0.75rem; color: var(--danger); cursor: pointer; font-weight: 600; width: 100%;">
                                        <input type="checkbox" name="delete_existing_files[]" value="<?= htmlspecialchars($f) ?>"> Delete
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="portfolio.php" class="btn-primary" style="background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none; text-decoration: none; display: inline-flex; align-items: center;">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Project</span>
                </button>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- List of Projects -->
    <div style="display: flex; justify-content: flex-end;">
        <a href="portfolio.php?action=add" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i>
            <span>Add Project</span>
        </a>
    </div>
    
    <div class="table-card">
        <div class="table-header">
            <h2>Project List</h2>
        </div>
        
        <?php if (empty($projects)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-gem" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 15px; display: block;"></i>
                <p>No projects uploaded yet. Click the button above to add your first project.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Project Title</th>
                            <th style="width: 35%;">Description</th>
                            <th style="width: 15%;">Attachment</th>
                            <th style="width: 15%;">Link</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--dark);">
                                    <?= htmlspecialchars($p['title']) ?>
                                </td>
                                <td style="font-size: 0.9rem; line-height: 1.5;">
                                    <?= htmlspecialchars($p['description'] ?: '-') ?>
                                </td>
                                <td>
                                    <?php 
                                    $p_files = [];
                                    if (!empty($p['files']) && is_array($p['files'])) {
                                        $p_files = $p['files'];
                                    } elseif (!empty($p['file'])) {
                                        $p_files = [$p['file']];
                                    }
                                    
                                    if (!empty($p_files)): 
                                        foreach ($p_files as $f_idx => $f):
                                            if (file_exists(__DIR__ . '/../files/' . $f)):
                                    ?>
                                                <div style="margin-bottom: 6px;">
                                                    <a href="#" class="file-link" onclick="showAdminDoc('../files/<?= htmlspecialchars($f) ?>', '<?= htmlspecialchars(addslashes($p['title'])) ?>'); return false;">
                                                        <i class="fa-solid fa-file-invoice"></i> File <?= $f_idx + 1 ?>
                                                    </a>
                                                </div>
                                    <?php 
                                            endif;
                                        endforeach;
                                    else: 
                                    ?>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">No files</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['link'])): ?>
                                        <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" class="file-link">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Visit
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: center;">
                                        <a href="portfolio.php?action=edit&id=<?= $p['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="portfolio.php?action=delete&id=<?= $p['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars(addslashes($p['title'])) ?>?');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
function showAdminDoc(fileUrl, title) {
    const ext = fileUrl.split('.').pop().toLowerCase();
    let content = '';
    
    if (ext === 'pdf') {
        content = `<iframe src="${fileUrl}" style="width:100%; height:500px; border:none;" title="${title}"></iframe>`;
    } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
        content = `<img src="${fileUrl}" style="width:100%; max-height:500px; object-fit:contain; border-radius:8px;" alt="${title}">`;
    } else {
        content = `<p>Cannot preview this format. <a href="${fileUrl}" target="_blank" class="file-link">Download instead</a></p>`;
    }
    
    showAdminModal(content);
}
</script>

<?php
require_once __DIR__ . '/layout_footer.php';
?>
