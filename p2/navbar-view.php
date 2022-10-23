<nav class="navbar bg-light p-2 mb-4">
    <div class="fs-2">
        E2 BlackJack
    </div>
    <div class="fs-2">
        Cash Balance $<?php echo $setup["cash"]; ?>
    </div>

    <div>
        <form method="GET" action="process.php">
            <input type="hidden" name="quit" id="quit">
            <button type="submit" class="btn btn-danger">Quit ;(</button>
        </form>
    </div>
</nav>