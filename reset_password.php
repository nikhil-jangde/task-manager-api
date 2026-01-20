<?php
use Illuminate\Support\Facades\Hash;
use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'test@example.com')->first();
if ($user) {
    $user->password = Hash::make('password');
    $user->save();
    echo "Password reset successfully for " . $user->email . "\n";
} else {
    echo "User not found\n";
}
