<?php

require_once __DIR__ . '/config.php';
ensure_authenticated();

$db = get_database_connection();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'list';
}

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
} else {
    $message = '';
}

function validate_employee_input(array $input): array {
    $data = [
        'employee_no'     => trim($input['employee_no'] ?? ''),
        'first_name'      => trim($input['first_name'] ?? ''),
        'last_name'       => trim($input['last_name'] ?? ''),
        'position_title'  => trim($input['position_title'] ?? ''),
        'department_name' => trim($input['department_name'] ?? ''),
        'status'          => $input['status'] ?? 'active',
        // optional fields
        'gender'          => $input['gender'] ?? null,
        'date_of_birth'   => ($input['date_of_birth'] ?? '') ?: null,
        'work_email'      => trim($input['work_email'] ?? ''),
        'mobile_no'       => trim($input['mobile_no'] ?? ''),
        'hire_date'       => ($input['hire_date'] ?? '') ?: null,
    ];

    $errors = [];
    if ($data['employee_no'] === '') {
        $errors[] = 'Employee No is required.';
    }
    if ($data['first_name'] === '' || $data['last_name'] === '') {
        $errors[] = 'First and Last name are required.';
    }
    if ($data['work_email'] !== '' && !filter_var($data['work_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid work email format.';
    }
    return [$errors, $data];
}

if (is_post_request()) {
    if ($action === 'create' || $action === 'edit') {
        [$form_errors, $employee_data] = validate_employee_input($_POST);
        if (empty($form_errors)) {
            try {
                if ($action === 'create') {
                    $sql = "INSERT INTO employees
                            (employee_no, first_name, last_name, position_title, department_name, status,
                             gender, date_of_birth, work_email, mobile_no, hire_date)
                            VALUES (:employee_no,:first_name,:last_name,:position_title,:department_name,:status,
                                    :gender,:date_of_birth,:work_email,:mobile_no,:hire_date)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute($employee_data);
                    header('Location: /PHP/CRUD_PHP/employees.php?msg=created');
                    exit;
                } else {
                    if (isset($_GET['id'])) {
                        $employee_id = (int)$_GET['id'];
                    } else {
                        $employee_id = 0;
                    }
                    $employee_data['id'] = $employee_id;

                    $sql = "UPDATE employees SET
                                employee_no=:employee_no,
                                first_name=:first_name,
                                last_name=:last_name,
                                position_title=:position_title,
                                department_name=:department_name,
                                status=:status,
                                gender=:gender,
                                date_of_birth=:date_of_birth,
                                work_email=:work_email,
                                mobile_no=:mobile_no,
                                hire_date=:hire_date
                            WHERE id=:id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute($employee_data);
                    header('Location: /PHP/CRUD_PHP/employees.php?msg=updated');
                    exit;
                }
            } catch (PDOException $ex) {
                $errno = $ex->errorInfo[1] ?? null;
                if ($errno == 1062) {
                    $form_errors[] = 'Employee No or Work Email already exists.';
                } else {
                    $form_errors[] = 'Database error: ' . escape_html($ex->getMessage());
                }
            }
        }
    } elseif ($action === 'delete') {
        if (isset($_POST['id'])) {
            $employee_id = (int)$_POST['id'];
        } else {
            $employee_id = 0;
        }
        if ($employee_id > 0) {
            $stmt = $db->prepare("DELETE FROM employees WHERE id = ?");
            $stmt->execute([$employee_id]);
        }
        header('Location: /PHP/CRUD_PHP/employees.php?msg=deleted');
        exit;
    }
}

$employee_row = null;
if ($action === 'edit') {
    if (isset($_GET['id'])) {
        $employee_id = (int)$_GET['id'];
    } else {
        $employee_id = 0;
    }
    $stmt = $db->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$employee_id]);
    $employee_row = $stmt->fetch();
    if (!$employee_row) {
        http_response_code(404);
        exit('Employee not found.');
    }
}

$employees_list = [];
if ($action === 'list') {
    $employees_list = $db->query(
        "SELECT id, employee_no, first_name, last_name, position_title, department_name, status
         FROM employees ORDER BY last_name, first_name"
    )->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Employees | Simple HRMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="topbar">
    <div><strong>Human Resource Management System</strong></div>
    <div style="margin-left:auto" class="muted">
      <?= escape_html((get_authenticated_user()['email'] ?? '')) ?> —
      <a class="btn" href="/PHP/CRUD_PHP/logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <?php if ($message): ?>
      <div class="notice"><?= escape_html($message) ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
      <div class="card" style="margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0">Employees</h3>
        <a class="btn primary" href="/PHP/CRUD_PHP/employees.php?action=create">+ Add</a>
      </div>
      <div class="card">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Emp No</th>
              <th>Name</th>
              <th>Position</th>
              <th>Department</th>
              <th>Status</th>
              <th style="width:160px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($employees_list)): ?>
              <tr><td colspan="7" class="muted">No employees yet. Click “Add”.</td></tr>
            <?php else: foreach ($employees_list as $i => $row): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= escape_html($row['employee_no']) ?></td>
                <td><?= escape_html($row['last_name'] . ', ' . $row['first_name']) ?></td>
                <td><?= escape_html($row['position_title']) ?></td>
                <td><?= escape_html($row['department_name']) ?></td>
                <td><?= escape_html($row['status']) ?></td>
                <td>
                  <a class="btn" href="/PHP/CRUD_PHP/employees.php?action=edit&id=<?= (int)$row['id'] ?>">Edit</a>
                  <form style="display:inline" method="post" action="/PHP/CRUD_PHP/employees.php?action=delete" onsubmit="return confirm('Delete this employee?');">
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                    <button class="btn danger" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <?php if ($action === 'create' || $action === 'edit'):
      $is_edit = ($action === 'edit'); ?>
      <div class="card" style="margin-top:1rem">
        <h3 style="margin-top:0"><?= $is_edit ? 'Edit' : 'Add' ?> Employee</h3>

        <?php if (!empty($form_errors ?? [])): ?>
          <div class="error">
            <?php foreach (($form_errors ?? []) as $msg) echo '<div>'.escape_html($msg).'</div>'; ?>
          </div>
        <?php endif; ?>

        <form method="post">
          <div class="row">
            <div>
              <label>Employee No</label>
              <input name="employee_no" value="<?= escape_html($employee_row['employee_no'] ?? '') ?>" required>
            </div>
            <div>
              <label>First Name</label>
              <input name="first_name" value="<?= escape_html($employee_row['first_name'] ?? '') ?>" required>
            </div>
            <div>
              <label>Last Name</label>
              <input name="last_name" value="<?= escape_html($employee_row['last_name'] ?? '') ?>" required>
            </div>

            <div>
              <label>Position Title</label>
              <input name="position_title" value="<?= escape_html($employee_row['position_title'] ?? '') ?>">
            </div>
            <div>
              <label>Department Name</label>
              <input name="department_name" value="<?= escape_html($employee_row['department_name'] ?? '') ?>">
            </div>
            <div>
              <label>Status</label>
              <select name="status">
                <?php foreach (['active','inactive','terminated'] as $s): ?>
                  <option value="<?= $s ?>" <?= (($employee_row['status'] ?? '') === $s) ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label>Gender (optional)</label>
              <select name="gender">
                <?php
                  $options = ['', 'Male','Female','Other','Prefer not to say'];
                  $current = $employee_row['gender'] ?? '';
                  foreach ($options as $opt) {
                    $selected = ($opt === $current) ? 'selected' : '';
                    echo "<option $selected>" . escape_html($opt) . "</option>";
                  }
                ?>
              </select>
            </div>
            <div>
              <label>Date of Birth (optional)</label>
              <input type="date" name="date_of_birth" value="<?= escape_html($employee_row['date_of_birth'] ?? '') ?>">
            </div>
            <div>
              <label>Hire Date (optional)</label>
              <input type="date" name="hire_date" value="<?= escape_html($employee_row['hire_date'] ?? '') ?>">
            </div>

            <div>
              <label>Work Email (optional)</label>
              <input type="email" name="work_email" value="<?= escape_html($employee_row['work_email'] ?? '') ?>">
            </div>
            <div>
              <label>Mobile No (optional)</label>
              <input name="mobile_no" value="<?= escape_html($employee_row['mobile_no'] ?? '') ?>">
            </div>
          </div>

          <div style="margin-top:0.75rem">
            <button class="btn primary" type="submit"><?= $is_edit ? 'Update' : 'Save' ?></button>
            <a class="btn" href="/PHP/CRUD_PHP/employees.php">Cancel</a>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>