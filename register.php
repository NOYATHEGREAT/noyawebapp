<?php
require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\User;
use Carbon\Carbon;

session_start();

$user = new User();

// Redirect if already logged in
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $data = [
        'name' => $_POST['full-name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'address' => $_POST['address'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'birthdate' => $_POST['birthdate'] ?? null,
        'created_at' => Carbon::now('Asia/Manila')->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now('Asia/Manila')->format('Y-m-d H:i:s')
    ];

    // Basic validation
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        $error = 'Please fill in all required fields.';
    } else {
        $registered = $user->register($data);

        if ($registered === false) {
            $error = "Email is already registered.";
        } else {
            $success = "You have successfully registered! You may now <a href='login.php'>login</a>.";
        }
    }
}

include 'helpers/functions.php';
template('header.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5 mb-5">
            <h1 class="text-center mb-4">Register</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST" style="max-width: 400px; margin: auto;">
                <div class="mb-3">
                    <label for="full-name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="full-name"
                        class="form-control"
                        id="full-name"
                        value="<?php echo isset($_POST['full-name']) ? htmlspecialchars($_POST['full-name']) : ''; ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        id="email"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        required
                    >
                    <div class="form-text">We'll never share your email with anyone else.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        id="password"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        id="address"
                        value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        id="phone"
                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                    >
                </div>

                <div class="mb-3">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input
                        type="date"
                        name="birthdate"
                        class="form-control"
                        id="birthdate"
                        value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>"
                    >
                </div>

                <button type="submit" name="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
