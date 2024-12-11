<?php
session_start();

if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 1000; 
}

if (isset($_POST['spin'])) {
    if ($_SESSION['currency'] <= 0) {
        $message = "You cannot play anymore. Please reload your balance.";
    } else {
        $symbols = ['🍒', '🍋', '🍊', '🍉', '🍇', '🍀'];
        $results = [];
        for ($i = 0; $i < 3; $i++) {
            $results[] = $symbols[array_rand($symbols)];
        }

        if ($results[0] === $results[1] && $results[1] === $results[2]) {
            $winAmount = 50;
            $_SESSION['currency'] += $winAmount;
            $message = "You won $$winAmount! 🎉";
        } else {
            $winAmount = 0;
            $_SESSION['currency'] -= 10; 
            $message = "You lost $10. Better luck next time!";
        }
    }
}

if (isset($_POST['reload'])) {
    $_SESSION['currency'] = 1000;
    $message = "Your balance has been reloaded to $1000.";
}

if (isset($_POST['double_or_nothing'])) {
    if ($_SESSION['currency'] <= 0) {
        $message = "You cannot play anymore. Please reload your balance.";
    } else {
        $betAmount = 50;
        if ($_SESSION['currency'] < $betAmount) {
            $message = "You don't have enough currency to double or nothing.";
        } else {
            $_SESSION['currency'] -= $betAmount;
            $win = rand(0, 1); 
            if ($win) {
                $_SESSION['currency'] += $betAmount * 2;
                $message = "You won $$betAmount! Your new balance is $".$_SESSION['currency'].".";
            } else {
                $message = "You lost $$betAmount. Your new balance is $".$_SESSION['currency'].".";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gambling</title>
</head>
<body>
    <div class="container">
        <h1>Gambling</h1>
        <div class="currency">Currency: $<?php echo $_SESSION['currency']; ?></div>
        <div class="slot-machine">
            <div class="slot" id="slot1"><?php echo isset($results[0]) ? $results[0] : '❓'; ?></div>
            <div class="slot" id="slot2"><?php echo isset($results[1]) ? $results[1] : '❓'; ?></div>
            <div class="slot" id="slot3"><?php echo isset($results[2]) ? $results[2] : '❓'; ?></div>
        </div>
        <form method="post">
            <button type="submit" name="spin" id="spinButton" <?php echo ($_SESSION['currency'] <= 0) ? 'disabled' : ''; ?>>Spin</button>
            <button type="submit" name="reload">Reload Balance</button>
            <button type="submit" name="double_or_nothing" <?php echo ($_SESSION['currency'] <= 0) ? 'disabled' : ''; ?>>Double or Nothing</button>
        </form>
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
    <script>
        document.getElementById('spinButton').addEventListener('click', function() {
            const slots = document.querySelectorAll('.slot');
            const symbols = ['🍒', '🍋', '🍊', '🍉', '🍇', '🍀'];
            let spinIndex = 0;

            function spinSlot() {
                if (spinIndex < slots.length) {
                    slots[spinIndex].classList.add('spin');
                    setTimeout(() => {
                        slots[spinIndex].classList.remove('spin');
                        slots[spinIndex].textContent = symbols[Math.floor(Math.random() * symbols.length)];
                        spinIndex++;
                        spinSlot();
                    }, 1000);
                }
            }

            spinSlot();
        });
    </script>
</body>
</html>