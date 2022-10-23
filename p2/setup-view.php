<div class="col-6 m-2">
    <img src="dogs_poker.jpg" class="card-img-top" alt="A painting by Brad ">
    <div class="mt-5 text-center">
        <h2>E2 Project 2</h2>
        <h2>by Bradley Ross</h2>
    </div>
</div>

<div class="col-6 card m-2 p-3">
    <form method="POST" action="process.php">
        <div class="my-4">
            <label for="playerName" class="form-label">Your name</label>
            <input type="text" class="form-control" name="playerName" id="playerName" placeholder="Your name"
                value="Brad">
        </div>
        <div class="mb-4">
            <label for="cash" class="form-label">Starting Balance</label>
            <input type="text" class="form-control" name="cash" id="cash" value="100">
        </div>
        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="multiPlayer" name="multiPlayer" checked>
                <label class="form-check-label" for="multiPlayer">Multi Player Game</label>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <input type="hidden" name="setup" id="setup">
            <button type="submit" class="btn btn-primary mb-3">Get Started</button>
        </div>
    </form>
</div>