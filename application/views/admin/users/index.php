<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Manage Users</h2>
    <a href="<?php echo site_url('admin/users/add'); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New User
    </a>
</div>

<div class="card">
    <div class="card-header bg-light text-dark">
        <h5 class="mb-0">Users List</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($users)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user->user_id; ?></td>
                                <td>
                                    <strong><?php echo $user->full_name; ?></strong>
                                </td>
                                <td><?php echo $user->username; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo $user->phone ? $user->phone : '-'; ?></td>
                                <td>
                                    <span class="badge <?php echo $user->role == 'admin' ? 'bg-danger' : 'bg-secondary'; ?>">
                                        <?php echo ucfirst($user->role); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo site_url('admin/users/view/' . $user->user_id); ?>"
                                            class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('admin/users/edit/' . $user->user_id); ?>"
                                            class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('admin/users/delete/' . $user->user_id); ?>"
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No users found</h4>
                <p class="text-muted">Get started by adding your first user.</p>
                <a href="<?php echo site_url('admin/users/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>