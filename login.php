<?php
require 'vendor/autoload.php';

use Aries\MiniFrameworkStore\Models\User;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = new User();

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('Location: my-account.php');
    exit;
}

if (isset($_POST['submit'])) {
    $user_info = $user->login([
        'email' => $_POST['email'],
    ]);

    if ($user_info && password_verify($_POST['password'], $user_info['password'])) {
        $_SESSION['user'] = $user_info;
        header('Location: my-account.php');
        exit;
    } else {
        $message = 'Invalid username or password';
    }
}
?>

<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <h1 class="text-center mb-4">Login</h1>

            <?php if (isset($message)): ?>
                <div class="alert alert-danger text-center"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="shadow p-4 rounded bg-light">
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email address</label>
                    <input name="email" type="email" class="form-control" id="emailInput" aria-describedby="emailHelp" required>
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="passwordInput" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberCheck">
                    <label class="form-check-label" for="rememberCheck">Remember me</label>
                </div>
                <div class="d-grid">
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <p class="text-center mt-3">
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>
    </div>
</div>

<?php template('footer.php'); ?>
